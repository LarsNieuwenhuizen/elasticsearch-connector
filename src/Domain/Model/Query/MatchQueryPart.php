<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

class MatchQueryPart implements QueryPart
{

    private string $field;

    private string $value;

    public static function create(string $field, string $value): self
    {
        $object = new static();
        $object->field  = $field;
        $object->value = $value;
        return $object;
    }

    public function toArray(): array
    {
        return [
            'match' => [
                $this->field => $this->value
            ]
        ];
    }
}
