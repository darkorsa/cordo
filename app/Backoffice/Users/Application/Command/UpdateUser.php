<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command;

use DateTime;

class UpdateUser
{
    private $id;

    private $email;

    private $isActive;

    private $updatedAt;

    public function __construct(
        string $id,
        string $email,
        bool $isActive,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->isActive = $isActive;
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

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
