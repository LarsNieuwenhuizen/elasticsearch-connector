<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model\Query;

interface CompoundQuery
{

    /**
     * @return array
     */
    public function toArray(): array;
}
