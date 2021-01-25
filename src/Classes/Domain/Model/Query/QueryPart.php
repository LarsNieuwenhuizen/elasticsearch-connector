<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

interface QueryPart
{

    /**
     * @return array
     */
    public function toArray(): array;
}
