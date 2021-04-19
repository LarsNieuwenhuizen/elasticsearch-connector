<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Service;

use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexableDocumentInterface;
use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexableModelCollection;
use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexConfiguration;
use LarsNieuwenhuizen\EsConnector\Service\Elasticsearch;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class DocumentIndexer
{

    private Elasticsearch $elasticsearch;

    private LoggerInterface $logger;

    private string $indexPrefix;

    public function __construct(string $indexPrefix, Elasticsearch $elasticsearch, LoggerInterface $logger = null)
    {
        $this->indexPrefix = $indexPrefix;
        $this->logger = $logger ?? new NullLogger();
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * @return void
     */
    public function indexDocuments(): void
    {
        /** @var IndexConfiguration $configuration */
        foreach ($this->elasticsearch->getIndexConfigurationCollection() as $configuration) {
            $this->logger->info(
                \sprintf('Index configuration %s', $configuration->getIndexName())
            );

            $this->indexDocumentCollection(
                $configuration->getDocumentNodeIndexingModel()->getDocumentRepository()->getAllDocuments()
            );
        }
    }

    private function indexDocumentCollection(IndexableModelCollection $documents): void
    {
        /** @var IndexableDocumentInterface $document */
        foreach ($documents as $document) {
            $this->logger->debug(
                \sprintf(
                    'Index %s',
                    $document->toShortUuid()
                )
            );

            try {
               /** @var IndexableDocumentInterface $document */
                $this->elasticsearch->getClient()->index(
                    $document->toIndexStructureArray(
                        $this->indexPrefix
                    )
                );
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }
}
