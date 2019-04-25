<?php

use Noodlehaus\Config;
use League\Event\Emitter;
use System\UI\Http\Router;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use League\Event\EmitterInterface;
use Psr\Container\ContainerInterface;
use System\Application\Config\Parser;
use Psr\Http\Message\ServerRequestInterface;
use System\UI\Transformer\TransformerManager;
use System\Application\Error\ErrorReporterInterface;
use System\UI\Transformer\TransformerManagerInterface;
use System\Infractructure\Mailer\ZendMail\MailerInterface;

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
    ErrorReporterInterface::class => DI\get('error_reporter'),
    MailerInterface::class => DI\get('mailer'),
    EmitterInterface::class => DI\get(Emitter::class),
    EntityManager::class => DI\get('entity_manager'),
    TransformerManagerInterface::class => DI\get(TransformerManager::class),
    Connection::class => DI\factory(function (ContainerInterface $c) {
        return $c->get('entity_manager')->getConnection();
    }),
];
