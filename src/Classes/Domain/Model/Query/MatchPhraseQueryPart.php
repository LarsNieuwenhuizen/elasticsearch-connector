<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

class MatchPhraseQueryPart implements QueryPart
{

    protected string $field;

    protected string $value;

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
            'match_phrase' => [
                $this->field => $this->value
            ]
        ];
    }
}
