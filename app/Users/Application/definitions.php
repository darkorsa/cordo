<?php

use Psr\Container\ContainerInterface;
use App\Users\Application\Service\UserService;
use App\Users\Infrastructure\Doctrine\Query\UserQuery;
use App\Users\Infrastructure\Doctrine\ORM\UserRepository;
use App\Users\Application\Command\Handler\CreateNewUserHandler;

return [
    CreateNewUserHandler::class => DI\create()
        ->constructor(DI\get(UserRepository::class), DI\get('emitter')),
    UserQuery::class => DI\factory(function (ContainerInterface $c) {
        return new UserQuery($c->get('entity_manager')->getConnection());
    }),
    UserService::class => DI\create()
        ->constructor(DI\get(UserQuery::class))
];
