<?php

namespace App\Backoffice\Acl\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Cordo\Core\Infractructure\Persistance\Doctrine\Query\QueryBuilderFilter;

class AclDoctrineFilter extends QueryBuilderFilter
{
    public function doFilter(QueryBuilder $queryBuilder): void
    {
        if ($this->queryFilter->getFilter('idUser') !== null) {
            $queryBuilder
                ->andWhere('ouuid_to_uuid(a.id_user) = :userId')
                ->setParameter('userId', $this->queryFilter->getFilter('idUser'));
        }
    }
}
