<?php

namespace App\Users\Application\Query;

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
        string $createdAt,
        string $updatedAt
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
            $userData['is_active'],
            $userData['created_at'],
            $userData['updated_at']
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

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function updatedAt(): string
    {
        return $this->updatedAt;
    }
}
