<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

abstract class AbstractIndexableDocumentModel implements IndexableDocumentInterface
{

    abstract public function getModuleName(): string;

    abstract public static function createFromArrayForIndexing(array $data): IndexableDocumentInterface;

    public function toIndexStructureArray(?string $indexPrefix = null): array
    {
        return [
            'index' => ($indexPrefix ?? '') . $this->getIndexName(),
            'id' => $this->getIdForIndexing(),
            'body' => $this->getIndexBodyStructure()
        ];
    }
}
