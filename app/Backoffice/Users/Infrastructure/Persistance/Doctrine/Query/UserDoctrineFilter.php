<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Cordo\Core\Infractructure\Persistance\Doctrine\Query\QueryBuilderFilter;

class UserDoctrineFilter extends QueryBuilderFilter
{
    public function doFilter(QueryBuilder $queryBuilder): void
    {
        if ($this->queryFilter->getFilter('isActive') !== null) {
            $queryBuilder
                ->andWhere('u.is_active = :is_active')
                ->setParameter('is_active', $this->queryFilter->getFilter('isActive'));
        }

        if ($this->queryFilter->getFilter('idUser') !== null) {
            $queryBuilder
                ->andWhere('ouuid_to_uuid(u.id_user) = :userId')
                ->setParameter('userId', $this->queryFilter->getFilter('idUser'));
        }

        if ($this->queryFilter->getFilter('email') !== null) {
            $queryBuilder
                ->andWhere('email = :email')
                ->setParameter('email', $this->queryFilter->getFilter('email'));
        }
    }
}
