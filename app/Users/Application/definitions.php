<?php

use App\Users\Application\Service\UserService;
use App\Users\Application\Command\Handler\DeleteUserHandler;
use App\Users\Application\Command\Handler\UpdateUserHandler;
use App\Users\Application\Command\Handler\CreateNewUserHandler;
use App\Users\Infrastructure\Persistance\Doctrine\Query\UserQuery;
use App\Users\Infrastructure\Persistance\Doctrine\ORM\UserRepository;

return [
    CreateNewUserHandler::class => DI\create()
        ->constructor(DI\get(UserRepository::class), DI\get('emitter')),
    UpdateUserHandler::class => DI\create()
        ->constructor(DI\get(UserRepository::class), DI\get('emitter')),
    DeleteUserHandler::class => DI\create()
        ->constructor(DI\get(UserRepository::class), DI\get('emitter')),
    UserService::class => DI\create()
        ->constructor(DI\get(UserQuery::class)),
];
