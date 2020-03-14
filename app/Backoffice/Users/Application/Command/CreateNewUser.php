<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command;

use DateTime;

class CreateNewUser
{
    protected $email;

    protected $password;

    protected $createdAt;

    public function __construct(
        string $email,
        string $password,
        DateTime $createdAt
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
}
