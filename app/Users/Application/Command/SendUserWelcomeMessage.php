<?php

declare(strict_types=1);

namespace App\Users\Application\Command;

use System\Application\Queue\AbstractMessage;

class SendUserWelcomeMessage extends AbstractMessage
{
    private $email;

    private $locale;

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
