<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Service;

use Cordo\Core\Application\Query\QueryFilter;
use Doctrine\Common\Collections\ArrayCollection;
use App\Backoffice\Users\Application\Query\UserView;
use App\Backoffice\Users\Application\Query\UserQuery;
use Cordo\Core\Application\Query\QueryFilterInterface;

class UserQueryService
{
    private $userQuery;

    public function __construct(UserQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    public function getOneById(string $id, ?QueryFilterInterface $queryFilter = null): UserView
    {
        $userFilter = $queryFilter ?: new QueryFilter();
        $userFilter->addFilter('idUser', $id);

        return $this->userQuery->getOne($userFilter);
    }

    public function getOneByEmail(string $email, ?QueryFilterInterface $queryFilter = null): UserView
    {
        $userFilter = $queryFilter ?: new QueryFilter();
        $userFilter->addFilter('email', $email);

        return $this->userQuery->getOne($userFilter);
    }

    public function getCollection(?QueryFilterInterface $queryFilter = null): ArrayCollection
    {
        return $this->userQuery->getAll($queryFilter);
    }

    public function getCount(?QueryFilterInterface $queryFilter = null): int
    {
        return $this->userQuery->count($queryFilter);
    }
}
