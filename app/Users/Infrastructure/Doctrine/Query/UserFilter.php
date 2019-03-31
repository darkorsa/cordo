<?php

namespace App\Users\Infrastructure\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use App\Users\Application\Query\UserFilter as Filter;
use System\Infractructure\Doctrine\Query\QueryFilter;

class UserFilter implements QueryFilter
{
    private $userFilter;
    
    public function __construct(Filter $userFilter)
    {
        $this->userFilter = $userFilter;
    }

    public function filter(QueryBuilder $qb): void
    {
        if (!is_null($this->userFilter->getIsActive())) {
            $qb->andWhere('u.is_active', $this->userFilter->getIsActive());
        }

        if (!is_null($this->userFilter->getOffset()) && !is_null($this->userFilter->getLimit())) {
            $qb->setFirstResult($this->userFilter->getOffset());
            $qb->setMaxResults($this->userFilter->getLimit());
        }
    }
}
