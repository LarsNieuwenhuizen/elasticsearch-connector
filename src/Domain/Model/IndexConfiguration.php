<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

use Symfony\Component\Yaml\Yaml;

class IndexConfiguration
{

    private string $indexName;

    private array $settings;

    private array $mapping;

    private IndexableDocumentInterface $documentNodeIndexingModel;

    public function getIndexName(): string
    {
        return self::addIndexNamePrefix($this->indexName);
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function getDocumentNodeIndexingModel(): IndexableDocumentInterface
    {
        return $this->documentNodeIndexingModel;
    }

    public static function addIndexNamePrefix(string $indexName): string
    {
        if (\getenv('ELASTIC_ELASTICSEARCH_INDEX_PREFIX') && \getenv('APP_CONTEXT')) {
            $context = \strtolower(\str_replace('/', '_', \getenv('APP_CONTEXT')));
            return \getenv('ELASTIC_ELASTICSEARCH_INDEX_PREFIX') . $context . '_' . $indexName;
        }
        throw new \Exception(
            'Indices need to be created with a prefix and context! ELASTIC_ELASTICSEARCH_INDEX_PREFIX & APP_CONTEXT'
        );
    }

    public static function createFromYaml(string $pathToFile): self
    {
        $configuration = Yaml::parseFile($pathToFile);
        $object = new static();
        $object->indexName = $configuration['index_name'];
        $object->settings = $configuration['settings'];
        $object->mapping = $configuration['mapping'];
        $object->documentNodeIndexingModel = new $configuration['document_node_indexing_model'];
        return $object;
    }

    public function toIndexCreationArray(): array
    {
        return [
            'index' => $this->indexName,
            'body' => [
                'settings' => $this->settings,
                'mappings' => $this->mapping
            ]
        ];
    }
}
