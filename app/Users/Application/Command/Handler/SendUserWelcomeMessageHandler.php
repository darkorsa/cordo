<?php

namespace App\Users\Application\Command\Handler;

use System\Application\Command\Handler\AbstractHandler;
use App\Users\Application\Command\SendUserWelcomeMessage;

class SendUserWelcomeMessageHandler extends AbstractHandler
{
    public function handle(SendUserWelcomeMessage $command): void
    {
        echo 'welcome message!';
    }
}
