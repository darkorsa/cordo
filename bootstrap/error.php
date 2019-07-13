<?php

$debug = getenv('APP_DEBUG') === 'true';

ini_set('display_errors', (int) $debug);
ini_set('display_startup_errors', (int) $debug);
error_reporting(E_ALL & E_STRICT);

use Whoops\Run;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use System\Application\Error\ErrorReporter;
use System\Application\Error\Handler\EmailErrorHandler;
use System\Application\Error\Handler\LoggerErrorHandler;
use System\Application\Error\Handler\PrettyErrorHandler;

$errorReporter = new ErrorReporter();

// default handler
$logger = new Logger('errorlog');
$logger->pushHandler(new StreamHandler(storage_path().'logs/error.log', Logger::DEBUG));

$errorReporter->pushHandler(new LoggerErrorHandler($logger));

if ($debug) {
    $prettyHandler = (isset($isConsole) && $isConsole)
        ? new PlainTextHandler()
        : new PrettyPageHandler();
    $whoops = new Run();
    $whoops->pushHandler($prettyHandler);

    $errorReporter->pushHandler(new PrettyErrorHandler($whoops));
} else {
    $errorReporter->pushHandler(new EmailErrorHandler());
}

// set php error handlers
set_error_handler([$errorReporter, 'errorHandler']);
register_shutdown_function([$errorReporter, 'fatalErrorShutdownHandler']);
set_exception_handler([$errorReporter, 'exceptionHandler']);

return $errorReporter;
