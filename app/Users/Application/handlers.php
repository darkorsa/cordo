<?php

use App\Users\Application\Command\CreateNewUser;
use App\Users\Application\Command\CreateNewUserHandler;
use App\Users\Application\Command\SendUserWelcomeMessage;
use App\Users\Application\Command\SendUserWelcomeMessageHandler;

return [
    CreateNewUser::class => CreateNewUserHandler::class,
    SendUserWelcomeMessage::class => SendUserWelcomeMessageHandler::class
];
