<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

class MustNotQueryClause extends AbstractCompoundQueryClause
{

    public function __construct()
    {
        $this->type = CompoundQueryClause::CLAUSE_TYPE_MUST_NOT;
    }
}
