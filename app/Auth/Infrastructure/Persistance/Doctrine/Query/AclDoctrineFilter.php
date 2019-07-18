<?php

namespace App\Auth\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use App\Auth\Application\Query\AclFilter;
use System\Infractructure\Persistance\Doctrine\Query\QueryFilter;

class AclDoctrineFilter implements QueryFilter
{
    private $filter;

    public function __construct(?AclFilter $aclFilter)
    {
        $this->filter = $aclFilter;
    }

    public function filter(QueryBuilder $queryBuilder): void
    {
        if (is_null($this->filter)) {
            return;
        }

        if (!is_null($this->filter->getUserId())) {
            $queryBuilder->where('ouuid_to_uuid(a.id_user) = :userId')
                ->setParameter('userId', $this->filter->getUserId());
        }
    }
}
