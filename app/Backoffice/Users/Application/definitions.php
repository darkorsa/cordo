<?php

use App\Backoffice\Users\Application\Service\UserQueryService;
use App\Backoffice\Users\Application\Command\Handler\DeleteUserHandler;
use App\Backoffice\Users\Application\Command\Handler\UpdateUserHandler;
use App\Backoffice\Users\Application\Command\Handler\CreateNewUserHandler;
use App\Backoffice\Users\Infrastructure\Persistance\Doctrine\Query\UserDoctrineQuery;
use App\Backoffice\Users\Infrastructure\Persistance\Doctrine\ORM\UserDoctrineRepository;

return [
    CreateNewUserHandler::class => DI\create()
        ->constructor(
            DI\get(UserDoctrineRepository::class),
            DI\get('emitter')
        ),
    UpdateUserHandler::class => DI\create()
        ->constructor(
            DI\get(UserDoctrineRepository::class),
            DI\get('emitter')
        ),
    DeleteUserHandler::class => DI\create()
        ->constructor(
            DI\get(UserDoctrineRepository::class),
            DI\get('emitter')
        ),
    'users.query.service' => DI\create(UserQueryService::class)
        ->constructor(DI\get(UserDoctrineQuery::class)),
];
