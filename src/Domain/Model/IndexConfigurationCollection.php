<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

use ArrayObject;
use Countable;
use IteratorAggregate;
use Traversable;

class IndexConfigurationCollection implements IteratorAggregate, Countable
{

    private ArrayObject $indexConfigurations;

    public function __construct()
    {
        $this->indexConfigurations = new ArrayObject();
    }

    public function addIndexConfiguration(IndexConfiguration $indexConfiguration): void
    {
        $this->indexConfigurations->append($indexConfiguration);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->indexConfigurations as $key => $indexConfiguration) {
            yield $key => $indexConfiguration;
        }
    }

    public function count(): int
    {
        return $this->indexConfigurations->count();
    }
}
