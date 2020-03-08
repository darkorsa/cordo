<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Domain;

interface UserRepository
{
    public function find(string $id): User;

    public function add(User $user): void;

    public function update(User $user): void;

    public function delete(User $user): void;
}
