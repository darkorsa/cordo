<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$logger = new Logger('logger');
$logger->pushHandler(new StreamHandler(storage_path().'logs/error.log', Logger::ERROR));

try {
    $container = require_once __DIR__.'/bootstrap/app.php';
    return ConsoleRunner::createHelperSet($container->get('entity_manager'));
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
