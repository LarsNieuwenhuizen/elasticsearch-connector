<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

use ArrayObject;
use Countable;
use IteratorAggregate;
use Traversable;

class IndexableModelCollection implements IteratorAggregate, Countable
{

    protected ArrayObject $documents;

    public function __construct()
    {
        $this->documents = new ArrayObject();
    }

    public function addDocument(Indexable $document)
    {
        $this->documents->append($document);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->documents as $key => $value) {
            yield $key => $value;
        }
    }

    public function count(): int
    {
        return $this->documents->count();
    }
}
