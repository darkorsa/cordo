<?php

namespace App\Users\Application\Command;

use App\Users\Application\Command\SendUserWelcomeMessage;

class SendUserWelcomeMessageHandler
{
    public function handle(SendUserWelcomeMessage $command): void
    {
        echo 'welcome message!';
    }
}
