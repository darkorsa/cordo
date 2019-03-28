<?php

namespace System\Infractructure\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface QueryFilter
{
    public function filter(QueryBuilder $qb): void;
}
