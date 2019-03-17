<?php

use App\Users\Application\Command\CreateNewUser;
use App\Users\Application\Command\SendUserWelcomeMessage;
use App\Users\Application\Command\Handler\CreateNewUserHandler;
use App\Users\Application\Command\Handler\SendUserWelcomeMessageHandler;

return [
    CreateNewUser::class => CreateNewUserHandler::class,
    SendUserWelcomeMessage::class => SendUserWelcomeMessageHandler::class
];
