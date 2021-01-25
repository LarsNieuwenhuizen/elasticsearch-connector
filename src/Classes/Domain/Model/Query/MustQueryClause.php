<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

class MustQueryClause extends AbstractCompoundQueryClause
{

    public function __construct()
    {
        $this->type = CompoundQueryClause::CLAUSE_TYPE_MUST;
    }
}
