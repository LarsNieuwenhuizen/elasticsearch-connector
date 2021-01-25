<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Aggregation;

class TermsAggregation implements AggregationPart
{

    private string $field;

    private string $name;

    public static function create(string $name, string $field): TermsAggregation
    {
        $object = new static();
        $object->name = $name;
        $object->field = $field;
        return $object;
    }

    public function toArray(): array
    {
        return [
            'terms' => [
                'field' => $this->field
            ]
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
