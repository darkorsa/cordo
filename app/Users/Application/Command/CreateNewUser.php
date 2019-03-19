<?php declare(strict_types=1);

namespace App\Users\Application\Command;

use DateTime;

class CreateNewUser
{
    private $id;
    
    private $email;

    private $password;

    private $isActive;

    private $createdAt;

    public function __construct(string $id, string $email, string $password, int $isActive, DateTime $createdAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email() : string
    {
        return $this->email;
    }

    public function password() : string
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
}
