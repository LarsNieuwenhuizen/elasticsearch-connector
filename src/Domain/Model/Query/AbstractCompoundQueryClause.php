<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

abstract class AbstractCompoundQueryClause implements CompoundQueryClause
{

    protected string $type;

    /**
     * @var QueryPart[]
     */
    private array $queryParts;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param QueryPart $queryPart
     */
    public function addQueryPart(QueryPart $queryPart): void
    {
        $this->queryParts[] = $queryPart;
    }

    /**
     * @throws \Exception
     * @param bool $omitQualifier
     * @return array
     */
    public function toArray(bool $omitQualifier = true): array
    {
        if (empty($this->queryParts)) {
            throw new \Exception('No query parts given for ' . \get_class($this));
        }
        $result = [];
        foreach ($this->queryParts as $queryPart) {
            if ($omitQualifier === true) {
                $result[] = $queryPart->toArray();
            } else {
                $result[$this->type][] = $queryPart->toArray();
            }
        }
        return $result;
    }
}
