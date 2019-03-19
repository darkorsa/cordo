<?php declare(strict_types=1);

namespace App\Users\Domain;

use DateTime;
use Assert\Assert;

final class User
{
    private $id;

    private $email;

    private $password;

    private $isActive;

    private $createdAt;

    private $updatedAt;

    public function __construct(
        string $id,
        string $email,
        string $password,
        int $isActive,
        DateTime $createdAt,
        ?DateTime $updatedAt = null
    ) {
        Assert::that($id)->notEmpty()->uuid();
        Assert::that($email)->notEmpty()->maxLength(50)->email();
        Assert::that($password)->notEmpty()->minLength(6)->maxLength(18);
        Assert::that($isActive)->integer()->between(0, 1);

        $this->id = $id;
        $this->email = $email;
        $this->password = $this->hashPassword($password);
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }
}
