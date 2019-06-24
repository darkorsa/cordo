<?php declare(strict_types=1);

namespace App\Users\Application\Service;

use App\Users\Application\Query\UserView;
use App\Users\Application\Query\UserQuery;
use App\Users\Application\Query\UserFilter;
use Doctrine\Common\Collections\ArrayCollection;

class UserService
{
    private $userQuery;
    
    public function __construct(UserQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    public function getOneById(string $id, ?UserFilter $userFilter = null): UserView
    {
        return $this->userQuery->getById($id, $userFilter);
    }

    public function getOneByEmail(string $email, ?UserFilter $userFilter = null): UserView
    {
        return $this->userQuery->getByEmail($email, $userFilter);
    }

    public function getCollection(?UserFilter $userFilter = null): ArrayCollection
    {
        return $this->userQuery->getAll($userFilter);
    }

    public function getCount(?UserFilter $userFilter = null): int
    {
        return $this->userQuery->count($userFilter);
    }
}
