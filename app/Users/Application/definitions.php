<?php

use App\Users\Application\Service\UserService;
use App\Users\Infrastructure\Doctrine\Query\UserQuery;
use App\Users\Infrastructure\Doctrine\ORM\UserRepository;
use App\Users\Application\Command\Handler\CreateNewUserHandler;

return [
    CreateNewUserHandler::class => DI\create()
        ->constructor(DI\get(UserRepository::class), DI\get('emitter')),
    UserService::class => DI\create()
        ->constructor(DI\get(UserQuery::class)),
];
