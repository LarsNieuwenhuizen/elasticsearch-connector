<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Service;

use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexConfiguration;
use LarsNieuwenhuizen\EsConnector\Domain\Model\IndexConfigurationCollection;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use FilesystemIterator;
use Neos\Utility\Arrays;
use Symfony\Component\Yaml\Yaml;

class Elasticsearch
{

    private Client $client;

    private array $configuration;

    private IndexConfigurationCollection $indexConfigurationCollection;

    public function __construct()
    {
        $this->indexConfigurationCollection = new IndexConfigurationCollection();
        $elasticsearchConfigurationFilePath = \getenv('ELASTICSEARCH_CONFIGURATION_FILEPATH');
        $this->configuration = Yaml::parseFile($elasticsearchConfigurationFilePath)['Elasticsearch'];
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
        $indexConfigurationFiles = Arrays::getValueByPath($this->configuration, 'indexConfigurationFiles');
        $configurationFilePathPrefix = Arrays::getValueByPath($this->configuration, 'indexConfigurationFilePathPrefix');
        foreach ($indexConfigurationFiles as $configurationFile) {
            $files = new \GlobIterator(
                $configurationFilePathPrefix . $configurationFile,
                FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::CURRENT_AS_FILEINFO
            );
            foreach ($files as $file) {
                /** @var \SplFileInfo $file */
                $indexConfiguration = IndexConfiguration::createFromYaml($file->getRealPath());
                $this->indexConfigurationCollection->addIndexConfiguration($indexConfiguration);
            }
        }
    }
}
