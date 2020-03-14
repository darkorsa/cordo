<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Domain;

use DateTime;
use System\Domain\Aggregate\AggregateRoot;
use App\Backoffice\Users\Domain\Event\UserCreated;

class User extends AggregateRoot
{
    private $id;

    private $email;

    private $password;

    private $isActive;

    private $createdAt;

    private $updatedAt;

    public function __construct(
        UserId $id,
        UserEmail $email,
        UserPasswordHash $password,
        UserActive $isActive,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id->value();
        $this->email = $email->value();
        $this->password = $password->value();
        $this->isActive = $isActive->value();
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function created()
    {
        /**
         * This will emit an event that would be put on a queue.
         * Make sure you have Redis server installed and queue worker running in background
         * (see Queues section in README.md file)
         */
        $this->record(new UserCreated($this->email));
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

    public function active(): bool
    {
        return (bool) $this->isActive;
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
