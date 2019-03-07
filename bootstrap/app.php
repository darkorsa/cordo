<?php

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(root_path().'.env');

/**
 * DI container Psr\Container\ContainerInterface
 */
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/definitions.php');

if (getenv('APP_ENV') == 'production') {
    $containerBuilder->enableCompilation(storage_path().'cache');
}

$container = $containerBuilder->build();

$router = $container->get('router');

require_once(app_path() . 'UI/Http/routes.php');

return $container;
