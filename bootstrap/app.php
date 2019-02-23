<?php

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(root_path().'.env');

/**
 * DI container Psr\Container\ContainerInterface
 */
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(root_path().'config/definitions.php');

if (getenv('APP_ENV') == 'production') {
    $containerBuilder->enableCompilation(storage_path().'cache');
}

return $containerBuilder->build();
