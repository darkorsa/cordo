<?php

use Noodlehaus\Config;
use League\Event\Emitter;
use System\UI\Http\Router;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use League\Event\EmitterInterface;
use System\Application\Config\Parser;
use Psr\Http\Message\ServerRequestInterface;

return [
    'config'    => DI\factory(function () {
        return new Config(config_path(), new Parser());
    }),
    'lang'      => DI\factory(function () {
        return new Config(resources_path() . 'lang', new Parser());
    }),
    'request'   => DI\get(ServerRequestInterface::class),
    'router'    => DI\get(Router::class),
    'emitter'   => DI\get(EmitterInterface::class),
    ServerRequestInterface::class => DI\factory('GuzzleHttp\Psr7\ServerRequest::fromGlobals'),
    LoggerInterface::class => DI\get('logger'),
    EmitterInterface::class => DI\get(Emitter::class),
    EntityManager::class => DI\get('entity_manager'),
];
