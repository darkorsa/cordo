<?php

define('ENV_LOCAL', 'dev');
define('ENV_PRODUCTION', 'production');

use App\Loader;
use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use System\Infractructure\Mailer\ZendMail\MailerFactory;

$dotenv = new Dotenv();
$dotenv->load(root_path() . '.env');

// Errors
$errorReporter = require __DIR__ . '/error.php';

// DI container Psr\Container\ContainerInterface
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(Loader::loadDefinitions());

if (getenv('APP_ENV') === ENV_PRODUCTION) {
    $containerBuilder->enableCompilation(storage_path() . 'cache');
}

$container = $containerBuilder->build();
$container->set('error_reporter', $errorReporter);

// Configs
Loader::loadConfigs($container->get('config'));

// Acl
$acl = require __DIR__ . '/acl.php';
$container->set('acl', $acl);

// Mailer
$mailer = MailerFactory::factory($container->get('config')->get('mail'));
$container->set('mailer', $mailer);

// Database
$entityManager = require __DIR__ . '/db.php';
$container->set('entity_manager', $entityManager);

// Command bus
$commandBus = require __DIR__ . '/command_bus.php';
$container->set('command_bus', $commandBus);

return $container;
