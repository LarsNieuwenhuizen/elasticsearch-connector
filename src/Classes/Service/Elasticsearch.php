<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Service;

use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexConfiguration;
use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexConfigurationCollection;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use FilesystemIterator;
use Neos\Utility\Arrays;

class Elasticsearch
{

    private Client $client;

    private array $configuration;

    private IndexConfigurationCollection $indexConfigurationCollection;

    public function __construct()
    {
        $this->indexConfigurationCollection = new IndexConfigurationCollection();

        $this->loadIndexConfigurations();
        $this->client = ClientBuilder::fromConfig([
            'hosts' => Arrays::getValueByPath($this->configuration, 'hosts')
        ]);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return IndexConfigurationCollection
     */
    public function getIndexConfigurationCollection(): IndexConfigurationCollection
    {
        return $this->indexConfigurationCollection;
    }

    /**
     * Create the index configuration collections
     * @return void
     */
    private function loadIndexConfigurations(): void
    {
        $indexEligibleModules = Arrays::getValueByPath($this->configuration, 'indexEligibleModules');
        foreach ($indexEligibleModules as $eligibleModule) {
            $path = $this->getIndexConfigurationFilePathForModule($eligibleModule);
            $files = new \GlobIterator(
                $path,
                FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::CURRENT_AS_FILEINFO
            );
            foreach ($files as $file) {
                /** @var \SplFileInfo $file */
                $indexConfiguration = IndexConfiguration::createFromYaml($file->getRealPath());
                $this->indexConfigurationCollection->addIndexConfiguration($indexConfiguration);
            }
        }
    }

    /**
     * @param string $module
     * @return string
     */
    private function getIndexConfigurationFilePathForModule(string $module): string
    {
        return ROOT_PATH . 'App' . DIRECTORY_SEPARATOR . 'Module' . DIRECTORY_SEPARATOR . $module .
            DIRECTORY_SEPARATOR . 'Search' . DIRECTORY_SEPARATOR . 'index_configuration*.yaml';
    }
}
