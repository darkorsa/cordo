<?php

use App\Users\Application\Command\DeleteUser;
use App\Users\Application\Command\UpdateUser;
use App\Users\Application\Command\CreateNewUser;
use App\Users\Application\Command\SendUserWelcomeMessage;
use App\Users\Application\Command\Handler\DeleteUserHandler;
use App\Users\Application\Command\Handler\UpdateUserHandler;
use App\Users\Application\Command\Handler\CreateNewUserHandler;
use App\Users\Application\Command\Handler\SendUserWelcomeMessageHandler;

return [
    CreateNewUser::class            => CreateNewUserHandler::class,
    UpdateUser::class               => UpdateUserHandler::class,
    DeleteUser::class               => DeleteUserHandler::class,
    SendUserWelcomeMessage::class   => SendUserWelcomeMessageHandler::class,
];
