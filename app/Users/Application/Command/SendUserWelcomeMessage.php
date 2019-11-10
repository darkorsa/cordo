<?php

declare(strict_types=1);

namespace App\Users\Application\Command;

use System\Application\Queue\AbstractMessage;

class SendUserWelcomeMessage extends AbstractMessage
{
    private $email;

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
