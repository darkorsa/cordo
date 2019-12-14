<?php

declare(strict_types=1);

namespace System\Module\Auth\Application\Service;

class AuthService implements AuthServiceInterface
{
    public function hashPassword(string $password): string
    {
        if (defined('PASSWORD_ARGON2ID')) {
            return (string) password_hash($password, PASSWORD_ARGON2ID);
        }

        return (string) password_hash($password, PASSWORD_ARGON2I);
    }
}
