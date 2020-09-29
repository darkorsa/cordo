<?php

$debug = env('APP_DEBUG') === 'true';

ini_set('display_errors', (int) $debug);
ini_set('display_startup_errors', (int) $debug);
error_reporting(-1);

use Noodlehaus\Config;
use Cordo\Core\Application\Config\Parser;
use Cordo\Core\Application\Error\ErrorReporter;
use Cordo\Core\Application\Error\ErrorReporterBuilder;

$config = new Config(config_path(), new Parser());

$errorReporter = (new ErrorReporterBuilder(new ErrorReporter(), $config, env('APP_ENV'), $debug))->build();

// set php error handlers
set_error_handler([$errorReporter, 'errorHandler']);
register_shutdown_function([$errorReporter, 'fatalErrorShutdownHandler']);
set_exception_handler([$errorReporter, 'exceptionHandler']);

return $errorReporter;
