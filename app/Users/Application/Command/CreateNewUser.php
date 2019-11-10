<?php

declare(strict_types=1);

namespace App\Users\Application\Command;

use DateTime;

class CreateNewUser
{
    protected $id;

    protected $email;

    protected $password;

    protected $isActive;

    protected $createdAt;

    protected $updatedAt;

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
