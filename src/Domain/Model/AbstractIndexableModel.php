<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

abstract class AbstractIndexableModel implements Indexable
{

    abstract public function getModuleName(): string;

    abstract public static function createFromArrayForIndexing(array $data): Indexable;

    public function toIndexStructureArray(?string $indexPrefix = null): array
    {
        return [
            'index' => ($indexPrefix ?? '') . $this->getIndexName(),
            'id' => $this->getIdForIndexing(),
            'body' => $this->getIndexBodyStructure()
        ];
    }

    abstract public function getDocumentsToIndex(): IndexableModelCollection;
}
