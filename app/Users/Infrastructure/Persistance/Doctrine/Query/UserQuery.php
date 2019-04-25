<?php declare(strict_types=1);

namespace App\Users\Infrastructure\Persistance\Doctrine\Query;

use App\Users\Application\Query\UserView;
use App\Users\Application\Query\UserFilter;
use Doctrine\Common\Collections\ArrayCollection;
use System\Infractructure\Persistance\Doctrine\Query\BaseQuery;
use App\Users\Application\Query\UserQuery as UserQueryInterface;
use App\Users\Infrastructure\Persistance\Doctrine\Query\UserFilter as DoctrineUserFilter;

class UserQuery extends BaseQuery implements UserQueryInterface
{
    public function count(UserFilter $userFilter = null): int
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('count(u.id_user)')
            ->from('user', 'u');

        return (int) $this->column($queryBuilder, new DoctrineUserFilter($userFilter));
    }

    public function getById(string $userId, UserFilter $userFilter = null): UserView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('u.*, ouuid_to_uuid(u.id_user) as id_user')
            ->from('user', 'u')
            ->where('ouuid_to_uuid(u.id_user) = :userId')
            ->setParameter('userId', $userId);

        $userData = $this->assoc($queryBuilder, new DoctrineUserFilter($userFilter));
        
        return UserView::fromArray($userData);
    }

    public function getByEmail(string $email, UserFilter $userFilter = null): UserView
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('u.*, ouuid_to_uuid(u.id_user) as id_user')
            ->from('user', 'u')
            ->where('email = :email')
            ->setParameter('email', $email);

        $userData = $this->assoc($queryBuilder, new DoctrineUserFilter($userFilter));


        return UserView::fromArray($userData);
    }

    public function getAll(UserFilter $userFilter = null): ArrayCollection
    {
        $queryBuilder = $this->createQB();
        $queryBuilder
            ->select('u.*, ouuid_to_uuid(u.id_user) as id_user')
            ->from('user', 'u');

        $usersData = $this->all($queryBuilder, new DoctrineUserFilter($userFilter));

        $collection = new ArrayCollection();
        array_map(function (array $userData) use ($collection) {
            $collection->add(UserView::fromArray($userData));
        }, $usersData);

        return $collection;
    }
}
