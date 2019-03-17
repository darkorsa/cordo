<?php declare(strict_types=1);

namespace App\Users\Application\Command;

class SendUserWelcomeMessage
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email() : string
    {
        return $this->email;
    }
}
