<?php

namespace App\Users\Application\Query;

use App\Users\Application\Query\UserView;
use App\Users\Application\Query\UserFilter;

interface UserQuery
{
    public function count(UserFilter $userFilter = null): int;

    public function getById(string $userId, UserFilter $userFilter = null): UserView;

    public function getAll(UserFilter $userFilter = null): array;
}
