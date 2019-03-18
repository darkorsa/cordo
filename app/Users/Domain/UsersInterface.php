<?php declare(strict_types=1);

namespace App\Users\Domain;

interface UsersInterface
{
    public function add(User $user) : void;
}
