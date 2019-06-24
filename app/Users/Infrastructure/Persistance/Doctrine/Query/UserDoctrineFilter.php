<?php

namespace App\Users\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use App\Users\Application\Query\UserFilter;
use System\Infractructure\Persistance\Doctrine\Query\QueryFilter;

class UserDoctrineFilter implements QueryFilter
{
    private $userFilter;

    public function __construct(?UserFilter $userFilter)
    {
        $this->userFilter = $userFilter;
    }

    public function filter(QueryBuilder $queryBuilder): void
    {
        if (is_null($this->userFilter)) {
            return;
        }

        if (!is_null($this->userFilter->getIsActive())) {
            $queryBuilder->andWhere('u.is_active', $this->userFilter->getIsActive());
        }

        if (!is_null($this->userFilter->getOffset()) && !is_null($this->userFilter->getLimit())) {
            $queryBuilder->setFirstResult($this->userFilter->getOffset());
            $queryBuilder->setMaxResults($this->userFilter->getLimit());
        }
    }
}
