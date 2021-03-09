<?php

use App\Register;
use Dotenv\Dotenv;
use Ramsey\Uuid\Uuid;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Cordo\Core\SharedKernel\Uuid\Helper\UuidFactoryHelper;
use Cordo\Core\Infractructure\Mailer\ZendMail\MailerFactory;

$dotenv = Dotenv::createImmutable(root_path());
$dotenv->load();

// Errors
$errorReporter = require __DIR__ . '/error.php';

// UUID config
Uuid::setFactory(UuidFactoryHelper::getUuidFactory());

/**
 * @var $container Psr\Container\ContainerInterface
 */
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(Register::registerDefinitions());
$containerBuilder->useAutowiring(true);

if (isProductionEnv()) {
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

// Views
$templates = new Engine();
Register::registerViews($templates);
$container->set('templates', $templates);

// translations
$translator = require __DIR__ . '/translations.php';
$container->set('translator', $translator);

return $container;
