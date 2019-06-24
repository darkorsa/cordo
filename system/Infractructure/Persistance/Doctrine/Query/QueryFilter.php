<?php

namespace System\Infractructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface QueryFilter
{
    public function filter(QueryBuilder $queryBuilder): void;
}
