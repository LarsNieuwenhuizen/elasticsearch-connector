<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Aggregation;

interface AggregationPart
{

    public function getName(): string;

    public function toArray(): array;
}
