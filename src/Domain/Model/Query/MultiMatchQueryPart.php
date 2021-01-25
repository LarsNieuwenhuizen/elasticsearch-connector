<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

class MultiMatchQueryPart implements QueryPart
{

    private array $fields;

    private string $query;

    public static function create(array $fields, string $query): self
    {
        $object = new static();
        $object->fields = $fields;
        $object->query = $query;
        return $object;
    }

    public function toArray(): array
    {
        $result = [
            'multi_match' => [
                'query' => $this->query,
                'fields' => []
            ]
        ];
        foreach ($this->fields as $field) {
            $result['multi_match']['fields'][] = $field;
        }

        return $result;
    }
}
