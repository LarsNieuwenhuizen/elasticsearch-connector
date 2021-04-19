<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

interface IndexableDocumentInterface
{

    /**
     * Return this document converted to the array needed to index into Elasticsearch
     * For example
     * return [
     *      'index' => $this->getIndexName(),
     *      'id' => $this->getIdForIndexing(),
     *      'body' => $this->getIndexBodyStructure()
     * ];
     *
     * @param string|null $indexPrefix
     * @return array
     */
    public function toIndexStructureArray(?string $indexPrefix = null): array;

    public function getIdForIndexing(): string;

    public function getIndexBodyStructure(): array;

    public function getIndexName(): string;

    public function getDocumentRepository(): IndexableDocumentRepositoryInterface;

    public function toShortUuid();
}
