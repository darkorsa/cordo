<?php

namespace App\Users\Application\Query;

use App\Users\Application\Query\UserView;
use System\Infractructure\Query\QueryFilter;

interface UserQuery
{
    public function count(QueryFilter $userFilter = null): int;

    public function getById(string $userId, QueryFilter $userFilter = null): UserView;

    public function getAll(QueryFilter $userFilter = null): array;
}
