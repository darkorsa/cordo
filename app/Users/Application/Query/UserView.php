<?php

namespace App\Users\Application\Query;

use DateTime;

class UserView
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
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromArray(array $userData): UserView
    {
        return new UserView(
            $userData['id_user'],
            $userData['email'],
            $userData['password'],
            (int) $userData['is_active'],
            new DateTime($userData['created_at']),
            new DateTime($userData['updated_at'])
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function isActive(): int
    {
        return $this->isActive;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
