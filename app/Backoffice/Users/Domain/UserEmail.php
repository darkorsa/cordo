<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Domain;

use Assert\Assert;

class UserEmail
{
    public const EMAIL_MAX_LENGTH = 50;
    
    private string $email;

    public function __construct(string $email)
    {
        Assert::that($email)
            ->notEmpty()
            ->maxLength(static::EMAIL_MAX_LENGTH)
            ->email();

        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
