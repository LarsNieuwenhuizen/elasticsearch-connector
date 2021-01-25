<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Service;

use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexConfiguration;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class IndexManager
{

    private Elasticsearch $elasticsearch;

    private array $configuration;

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->elasticsearch = new Elasticsearch();
    }

    public function getIndices(): array
    {
        return $this->elasticsearch->getClient()->indices()->get([
            'index' => '*-' . IndexConfiguration::addIndexNamePrefix('*'),
        ]);
    }

    public function purgeStaleIndices(): void
    {
        $definedIndexNames = [];
        /** @var IndexConfiguration $indexConfiguration */
        foreach ($this->elasticsearch->getIndexConfigurationCollection() as $indexConfiguration) {
            $definedIndexNames[] = $indexConfiguration->getIndexName();
        }

        foreach ($this->getIndices() as $indexName => ['aliases' => $aliases]) {
            if ($aliases !== []) {
                $aliasName = \array_keys($aliases)[0];
                if (!\in_array($aliasName, $definedIndexNames)) {
                    $this->logger->info(
                        \sprintf('Removed old index %s', $indexName)
                    );
                    $this->elasticsearch->getClient()->indices()->delete([
                        'index' => $indexName,
                    ]);

                    continue;
                }

                $this->logger->info(
                    \sprintf('Ignore aliased index %s', $indexName)
                );
                continue;
            }

            $this->elasticsearch->getClient()->indices()->delete([
                'index' => $indexName,
            ]);

            $this->logger->info(
                \sprintf('Removed stale index %s', $indexName)
            );
        }
    }

    public function createIndices(string $indexPrefix): void
    {
        /** @var IndexConfiguration $configuration */
        foreach ($this->elasticsearch->getIndexConfigurationCollection() as $configuration) {
            $indexName = $indexPrefix . $configuration->getIndexName();
            if (!$this->elasticsearch->getClient()->indices()->exists(['index' => $indexName])) {
                $this->logger->info('Construct index ' . $indexName);
                $this->elasticsearch->getClient()->indices()->create([
                    'index' => $indexName,
                    'body' => [
                        'settings' => $configuration->getSettings(),
                        'mappings' => $configuration->getMapping(),
                    ],
                ]);
            } else {
                $this->logger->info('Ignore index as it already exists: ' . $indexName);
            }
        }
    }

    public function switchAliases(string $indexPrefix): void
    {
        /** @var IndexConfiguration $configuration */
        foreach ($this->elasticsearch->getIndexConfigurationCollection() as $configuration) {
            $aliasName = $configuration->getIndexName();
            $indexName = $indexPrefix . $aliasName;

            if ($this->elasticsearch->getClient()->indices()->existsAlias(['name' => $aliasName])) {
                $existingAliases = $this->elasticsearch->getClient()->indices()->getAlias(['name' => $aliasName]);
                foreach ($existingAliases as $existingAliasTargetIndexName => $existingAlias) {
                    $this->logger->info(
                        "Delete alias and index $indexName to $existingAliasTargetIndexName"
                    );
                    $this->elasticsearch->getClient()->indices()->deleteAlias([
                        'index' => $existingAliasTargetIndexName,
                        'name' => $aliasName,
                    ]);
                }
            }

            $this->elasticsearch->getClient()->indices()->putAlias([
                'name' => $aliasName,
                'index' => $indexName,
            ]);
        }
    }
}
