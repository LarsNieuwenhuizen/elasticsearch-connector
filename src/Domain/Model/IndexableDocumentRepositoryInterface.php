<?php
declare(strict_types=1);

namespace LarsNieuwenhuizen\EsConnector\Domain\Model;

interface IndexableDocumentRepositoryInterface
{

    public function getAllDocuments(): IndexableModelCollection;
}
