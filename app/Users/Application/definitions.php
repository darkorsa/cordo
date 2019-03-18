<?php

use App\Users\Infrastructure\Doctrine\DoctrineUsers;
use App\Users\Application\Command\Handler\CreateNewUserHandler;

return [
    CreateNewUserHandler::class => DI\create()
        ->constructor(DI\get(DoctrineUsers::class), DI\get('emitter')),
];
