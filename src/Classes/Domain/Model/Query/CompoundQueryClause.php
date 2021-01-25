<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

interface CompoundQueryClause
{

    const CLAUSE_TYPE_MUST = 'must';
    const CLAUSE_TYPE_SHOULD = 'should';
    const CLAUSE_TYPE_MUST_NOT = 'must_not';
    const CLAUSE_TYPE_FILTER = 'filter';

    /**
     * @param QueryPart $queryPart
     */
    public function addQueryPart(QueryPart $queryPart): void;

    /**
     * @return array
     */
    public function toArray(): array;
}
