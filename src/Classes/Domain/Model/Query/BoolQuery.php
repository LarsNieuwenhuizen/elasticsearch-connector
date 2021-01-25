<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

/**
 * This object is meant as the start of an elasticsearch bool query
 *
 * Note that bool queries in elasticsearch can be nested so this object is also a query part by design
 */
class BoolQuery implements QueryPart, CompoundQuery
{

    protected MustQueryClause $mustQueryClause;

    protected ShouldQueryClause $shouldQueryClause;

    protected MustNotQueryClause $mustNotQueryClause;

    protected FilterQueryClause $filterQueryClause;

    public function getMustQueryClause(): MustQueryClause
    {
        return $this->mustQueryClause;
    }

    public function getShouldQueryClause(): ShouldQueryClause
    {
        return $this->shouldQueryClause;
    }

    public function getMustNotQueryClause(): MustNotQueryClause
    {
        return $this->mustNotQueryClause;
    }

    public function getFilterQueryClause(): FilterQueryClause
    {
        return $this->filterQueryClause;
    }

    public function addMustQueryClause(MustQueryClause $mustQueryClause): void
    {
        $this->mustQueryClause = $mustQueryClause;
    }

    public function addMustNotQueryClause(MustNotQueryClause $mustNotQueryClause): void
    {
        $this->mustNotQueryClause = $mustNotQueryClause;
    }

    public function addShouldQueryClause(ShouldQueryClause $shouldQueryClause): void
    {
        $this->shouldQueryClause = $shouldQueryClause;
    }

    public function addFilterQueryClause(FilterQueryClause $filterQueryClause): void
    {
        $this->filterQueryClause = $filterQueryClause;
    }

    public function toArray(): array
    {
        $compoundQuery = [
            'bool' => []
        ];
        if ($this->mustQueryClause instanceof MustQueryClause) {
            $compoundQuery['bool'][$this->mustQueryClause->getType()] = $this->mustQueryClause->toArray();
        }
        if ($this->mustNotQueryClause instanceof MustNotQueryClause) {
            $compoundQuery['bool'][$this->mustNotQueryClause->getType()] = $this->mustNotQueryClause->toArray();
        }
        if ($this->shouldQueryClause instanceof ShouldQueryClause) {
            $compoundQuery['bool'][$this->shouldQueryClause->getType()] = $this->shouldQueryClause->toArray();
        }
        if ($this->filterQueryClause instanceof FilterQueryClause) {
            $compoundQuery['bool'][$this->filterQueryClause->getType()] = $this->filterQueryClause->toArray();
        }
        return $compoundQuery;
    }
}
