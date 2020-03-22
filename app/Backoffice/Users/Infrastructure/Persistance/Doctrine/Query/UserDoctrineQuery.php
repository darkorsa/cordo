<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Infrastructure\Persistance\Doctrine\Query;

use App\Backoffice\Users\Application\Query\UserView;
use App\Backoffice\Users\Application\Query\UserFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Cordo\Core\Infractructure\Persistance\Doctrine\Query\BaseQuery;
use App\Backoffice\Users\Application\Query\UserQuery;

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
            ->select('u.*')
            ->addSelect('ouuid_to_uuid(u.id_user) as id_user')
            ->from('backoffice_user', 'u')
            ->where('ouuid_to_uuid(u.id_user) = :userId')
            ->setParameter('userId', $userId);

        $userData = $this->assoc($queryBuilder, new UserDoctrineFilter($userFilter));

        return UserView::fromArray($userData);
    }

    public function getByEmail(string $email, ?UserFilter $userFilter = null): UserView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('u.*')
            ->addSelect('ouuid_to_uuid(u.id_user) as id_user')
            ->from('backoffice_user', 'u')
            ->where('email = :email')
            ->setParameter('email', $email);

        $userData = $this->assoc($queryBuilder, new UserDoctrineFilter($userFilter));

        return UserView::fromArray($userData);
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
}
