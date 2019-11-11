<?php

use App\Register;
use DI\ContainerBuilder;
use System\SharedKernel\Enum\Env;
use Symfony\Component\Dotenv\Dotenv;
use System\Infractructure\Mailer\ZendMail\MailerFactory;

$dotenv = new Dotenv();
$dotenv->load(root_path() . '.env');

// Errors
$errorReporter = require __DIR__ . '/error.php';

/**
 * @var $container Psr\Container\ContainerInterface
 */
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(Register::registerDefinitions());

if (getenv('APP_ENV') === Env::PRODUCTION()) {
    $containerBuilder->enableCompilation(storage_path() . 'cache');
}

$container = $containerBuilder->build();
$container->set('error_reporter', $errorReporter);

// Configs
Register::registerConfigs($container->get('config'));

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
