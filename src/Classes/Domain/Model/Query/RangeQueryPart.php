<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

class RangeQueryPart implements QueryPart
{

    const RELATION_TYPE_INTERSECTS = 'INTERSECTS';
    const RELATION_TYPE_CONTAINS = 'CONTAINS';
    const RELATION_TYPE_WITHIN = 'WITHIN';

    private string $lessThenOrEqualTo;

    private string $greaterThenOrEqualTo;

    private string $lessThen;

    private string $greaterThen;

    private string $format;

    private string $relation;

    private string $timeZone;

    private float $boost;

    private string $field;

    public function getLessThenOrEqualTo(): string
    {
        return $this->lessThenOrEqualTo;
    }

    public function setLessThenOrEqualTo(string $lessThenOrEqualTo): RangeQueryPart
    {
        $this->lessThenOrEqualTo = $lessThenOrEqualTo;
        return $this;
    }

    public function getGreaterThenOrEqualTo(): string
    {
        return $this->greaterThenOrEqualTo;
    }

    public function setGreaterThenOrEqualTo(string $greaterThenOrEqualTo): RangeQueryPart
    {
        $this->greaterThenOrEqualTo = $greaterThenOrEqualTo;
        return $this;
    }

    public function getLessThen(): string
    {
        return $this->lessThen;
    }

    public function setLessThen(string $lessThen): RangeQueryPart
    {
        $this->lessThen = $lessThen;
        return $this;
    }

    public function getGreaterThen(): string
    {
        return $this->greaterThen;
    }

    public function setGreaterThen(string $greaterThen): RangeQueryPart
    {
        $this->greaterThen = $greaterThen;
        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): RangeQueryPart
    {
        $this->format = $format;
        return $this;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): RangeQueryPart
    {
        $constants = (new \ReflectionClass($this))->getConstants();
        if (!\in_array($relation, $constants)) {
            throw new \Exception('Invalid relation set');
        }
        $this->relation = $relation;
        return $this;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function setTimeZone(string $timeZone): RangeQueryPart
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    public function getBoost(): float
    {
        return $this->boost;
    }

    public function setBoost(float $boost): RangeQueryPart
    {
        $this->boost = $boost;
        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function toArray(): array
    {
        $result = [
            'range' => [
                $this->getField() => []
            ]
        ];

        if (isset($this->greaterThen)) {
            $result['range'][$this->getField()]['gt'] = $this->getGreaterThen();
        }
        if (isset($this->greaterThenOrEqualTo)) {
            $result['range'][$this->getField()]['gte'] = $this->getGreaterThenOrEqualTo();
        }
        if (isset($this->lessThen)) {
            $result['range'][$this->getField()]['lt'] = $this->getLessThen();
        }
        if (isset($this->lessThenOrEqualTo)) {
            $result['range'][$this->getField()]['lte'] = $this->getLessThenOrEqualTo();
        }
        if (isset($this->boost)) {
            $result['range'][$this->getField()]['boost'] = $this->getBoost();
        }
        if (isset($this->relation)) {
            $result['range'][$this->getField()]['relation'] = $this->getRelation();
        }

        return $result;
    }

    public static function create(string $field): self
    {
        $object = new static();
        $object->field = $field;
        return $object;
    }
}
