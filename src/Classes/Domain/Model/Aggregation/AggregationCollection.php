<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Aggregation;

use ArrayObject;
use IteratorAggregate;
use Traversable;

class AggregationCollection implements IteratorAggregate
{

    private ArrayObject $aggregations;

    public function __construct()
    {
        $this->aggregations = new ArrayObject();
    }

    public function addAggregation(AggregationPart $aggregationPart): void
    {
        $this->aggregations->append($aggregationPart);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->aggregations as $aggregation) {
            /** @var AggregationPart $aggregation */
            yield $aggregation->getName() => $aggregation->toArray();
        }
    }

    public function toArray(): array
    {
        $aggregations = [];
        foreach ($this->aggregations as $aggregation) {
            $aggregations[$aggregation->getName()] = $aggregation->toArray();
        }
        return $aggregations;
    }
}
