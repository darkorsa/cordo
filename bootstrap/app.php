<?php

use App\Loader;
use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(root_path().'.env');

// DI container Psr\Container\ContainerInterface
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(Loader::loadDefinitions());

if (getenv('APP_ENV') == 'production') {
    $containerBuilder->enableCompilation(storage_path().'cache');
}

$container = $containerBuilder->build();
$container->set('logger', $logger);

// Configs
Loader::loadConfigs($container->get('config'));

// Database
$entityManager = require __DIR__.'/db.php';
$container->set('entity_manager', $entityManager);

// Command bus
$commandBus = require __DIR__.'/command_bus.php';
$container->set('command_bus', $commandBus);

return $container;
