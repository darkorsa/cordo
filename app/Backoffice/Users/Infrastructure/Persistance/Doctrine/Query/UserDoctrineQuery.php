<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Infrastructure\Persistance\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use App\Backoffice\Users\Application\Query\UserView;
use App\Backoffice\Users\Application\Query\UserQuery;
use App\Backoffice\Users\Application\Query\UserFilter;
use Cordo\Core\Infractructure\Persistance\Doctrine\Query\BaseQuery;

class UserDoctrineQuery extends BaseQuery implements UserQuery
{
    public function count(?UserFilter $userFilter = null): int
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('count(u.id_user)')
            ->from('backoffice_user', 'u');

        return (int) $this->column($queryBuilder, new UserDoctrineFilter($userFilter));
    }

    public function getById(string $userId, ?UserFilter $userFilter = null): UserView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->where('ouuid_to_uuid(u.id_user) = :userId')
            ->setParameter('userId', $userId);

        return $this->getOneByQuery($queryBuilder, $userFilter);
    }

    public function getByEmail(string $email, ?UserFilter $userFilter = null): UserView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->where('email = :email')
            ->setParameter('email', $email);

        return $this->getOneByQuery($queryBuilder, $userFilter);
    }

    public function getAll(?UserFilter $userFilter = null): ArrayCollection
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('u.*')
            ->addSelect('ouuid_to_uuid(u.id_user) as id_user')
            ->from('backoffice_user', 'u');

        $usersData = $this->all($queryBuilder, new UserDoctrineFilter($userFilter));

        $collection = new ArrayCollection();
        array_map(static function (array $userData) use ($collection) {
            $collection->add(UserView::fromArray($userData));
        }, $usersData);

        return $collection;
    }

    private function getOneByQuery(QueryBuilder $queryBuilder, ?UserFilter $userFilter = null): UserView
    {
        $queryBuilder
            ->select('u.*')
            ->addSelect('ouuid_to_uuid(u.id_user) as id_user')
            ->from('backoffice_user', 'u');

        $userData = $this->assoc($queryBuilder, new UserDoctrineFilter($userFilter));

        return UserView::fromArray($userData);
    }
}
