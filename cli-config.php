<?php

use Whoops\Run;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PlainTextHandler;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use System\Application\Error\Handler\PrettyErrorHandler;

require 'vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(root_path().'.env');

// pretty errors
$whoops = new Run;
$whoops->pushHandler(new PlainTextHandler);

$errorReporter = require_once __DIR__.'/bootstrap/error.php';
$errorReporter->pushHandler(new PrettyErrorHandler($whoops));

try {
    $container = require_once __DIR__.'/bootstrap/app.php';
    return ConsoleRunner::createHelperSet($container->get('entity_manager'));
} catch (Error | Exception $e) {
    $errorReporter->report($e);
}
