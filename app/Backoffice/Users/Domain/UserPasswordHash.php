<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Domain;

use Assert\Assert;

class UserPasswordHash
{
    private string $password;

    public function __construct(string $password)
    {
        Assert::that($password)->satisfy(static function ($password) {
            $info = password_get_info($password);
            if (!in_array($info['algoName'], ['argon2i', 'argon2id'])) {
                return false;
            }
            return true;
        });

        $this->password = $password;
    }

    public function value(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
