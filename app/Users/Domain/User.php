<?php declare(strict_types=1);

namespace App\Users\Domain;

use DateTime;
use Assert\Assert;

final class User
{
    public const EMAIL_MAX_LENGTH = 50;

    public const PASSWORD_MIN_LENGTH = 6;

    public const PASSWORD_MAX_LENGTH = 18;

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
        DateTime $updatedAt
    ) {
        // id
        Assert::that($id)
            ->notEmpty()
            ->uuid();
        // email
        Assert::that($email)
            ->notEmpty()
            ->maxLength(self::EMAIL_MAX_LENGTH)
            ->email();
        // passowrd
        Assert::that($password)
            ->notEmpty();
        // isActive
        Assert::that($isActive)
            ->integer()
            ->between(0, 1);

        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
