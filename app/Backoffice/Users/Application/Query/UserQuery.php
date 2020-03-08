<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;

interface UserQuery
{
    public function count(?UserFilter $userFilter = null): int;

    public function getById(string $userId, ?UserFilter $userFilter = null): UserView;

    public function getByEmail(string $email, ?UserFilter $userFilter = null): UserView;

    public function getAll(?UserFilter $userFilter = null): ArrayCollection;
}
