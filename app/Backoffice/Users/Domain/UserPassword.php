<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Domain;

use Assert\Assert;

class UserPassword
{
    public const PASSWORD_MIN_LENGTH = 6;

    public const PASSWORD_MAX_LENGTH = 18;
    
    private string $password;

    public function __construct(string $password)
    {
        Assert::that($password)
            ->notEmpty()
            ->betweenLength(self::PASSWORD_MIN_LENGTH, self::PASSWORD_MAX_LENGTH);

        $this->password = $password;
    }

    public function value(): string
    {
        return $this->hashPassword($this->password);
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }

    private function hashPassword(string $password): string
    {
        if (defined('PASSWORD_ARGON2ID')) {
            return (string) password_hash($password, PASSWORD_ARGON2ID);
        }

        return (string) password_hash($password, PASSWORD_ARGON2I);
    }
}
