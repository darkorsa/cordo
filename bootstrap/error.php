<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use System\Application\Error\ErrorReporter;
use System\Application\Error\Handler\LoggerErrorHandler;

$logger = new Logger('errorlog');
$logger->pushHandler(new StreamHandler(storage_path().'logs/error.log', Logger::DEBUG));

$errorReporter = new ErrorReporter();
$errorReporter->pushHandler(new LoggerErrorHandler($logger));

// for uncatchable fatal errors
set_error_handler([$errorReporter, 'myErrorHandler']);
register_shutdown_function([$errorReporter, 'fatalErrorShutdownHandler']);

return $errorReporter;
