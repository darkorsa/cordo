<?php

use App\Backoffice\Auth\Application\Service\AclQueryService;
use App\Backoffice\Auth\Application\Command\Handler\CreateUserAclHandler;
use App\Backoffice\Auth\Application\Command\Handler\DeleteUserAclHandler;
use App\Backoffice\Auth\Application\Command\Handler\UpdateUserAclHandler;
use App\Backoffice\Auth\Infrastructure\Persistance\Doctrine\Query\AclDoctrineQuery;
use App\Backoffice\Auth\Infrastructure\Persistance\Doctrine\ORM\AclDoctrineRepository;
use App\Backoffice\Users\Infrastructure\Persistance\Doctrine\ORM\UserDoctrineRepository;

return [
    CreateUserAclHandler::class => DI\create()
        ->constructor(
            DI\get(AclDoctrineRepository::class),
            DI\get(UserDoctrineRepository::class),
            DI\get('emitter')
        ),
    UpdateUserAclHandler::class => DI\create()
        ->constructor(
            DI\get(AclDoctrineRepository::class),
            DI\get(UserDoctrineRepository::class),
            DI\get('emitter')
        ),
    DeleteUserAclHandler::class => DI\create()
        ->constructor(
            DI\get(AclDoctrineRepository::class),
            DI\get('emitter')
        ),
    'backoffice.acl.query.service' => DI\create(AclQueryService::class)
        ->constructor(DI\get(AclDoctrineQuery::class)),
];
