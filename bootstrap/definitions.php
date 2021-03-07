<?php

use Monolog\Logger;
use Noodlehaus\Config;
use League\Event\Emitter;
use League\Plates\Engine;
use Doctrine\DBAL\Connection;
use Cordo\Core\UI\Http\Router;
use Doctrine\ORM\EntityManager;
use League\Event\EmitterInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Cordo\Core\Application\Config\Parser;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Translation\Translator;
use Cordo\Core\UI\Transformer\TransformerManager;
use Cordo\Core\Application\Error\ErrorReporterInterface;
use Cordo\Core\UI\Transformer\TransformerManagerInterface;
use Cordo\Core\Infractructure\Mailer\ZendMail\MailerInterface;

return [
    'config' => DI\factory(static function () {
        return new Config(config_path(), new Parser());
    }),
    'db_config' => DI\factory(static function (ContainerInterface $container) {
        $db = $container->get('config')->get('db');
        return $db['drivers'][$db['driver']];
    }),
    'lang' => DI\factory(static function () {
        return new Config(resources_path() . 'lang', new Parser());
    }),
    'request' => DI\get(ServerRequestInterface::class),
    'router' => DI\get(Router::class),
    'emitter' => DI\get(EmitterInterface::class),
    'logger' => DI\factory(static function () {
        return (new Logger('debug'))->pushHandler(new StreamHandler(storage_path('logs/debug.log'), Logger::DEBUG));
    }),
    ServerRequestInterface::class => DI\factory('GuzzleHttp\Psr7\ServerRequest::fromGlobals'),
    ErrorReporterInterface::class => DI\get('error_reporter'),
    MailerInterface::class => DI\get('mailer'),
    EmitterInterface::class => DI\get(Emitter::class),
    EntityManager::class => DI\get('entity_manager'),
    TransformerManagerInterface::class => DI\get(TransformerManager::class),
    Connection::class => DI\factory(static function (ContainerInterface $container) {
        return $container->get('entity_manager')->getConnection();
    }),
    Engine::class => DI\get('templates'),
    Translator::class => DI\get('translator'),
    Config::class => DI\get('config'),
    LoggerInterface::class => DI\get('logger'),
];
