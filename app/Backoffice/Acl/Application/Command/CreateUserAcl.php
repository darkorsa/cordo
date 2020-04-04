<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Application\Command;

use DateTime;
use App\Backoffice\Users\Application\Query\UserView;

class CreateUserAcl
{
    protected $id;

    protected $user;

    protected $privileges;

    protected $createdAt;

    protected $updatedAt;

    public function __construct(
        string $id,
        UserView $user,
        array $privileges,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->privileges = $privileges;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->user->id();
    }

    public function privileges(): array
    {
        return $this->privileges;
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
