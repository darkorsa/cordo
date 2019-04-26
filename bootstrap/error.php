<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use System\Application\Error\ErrorReporter;
use System\Application\Error\Handler\LoggerErrorHandler;

// for uncatchable fatal errors
if (getenv('APP_ENV') == 'production') {
    set_error_handler('myErrorHandler');
    register_shutdown_function('fatalErrorShutdownHandler');
}

function myErrorHandler($code, $message, $file, $line)
{
    $logger = new Logger('errorlog');
    $logger->pushHandler(new StreamHandler(storage_path().'logs/error.log', Logger::DEBUG));

    $logger->error("Fatal Error with code {$code} occured in file: {$file} on line {$line}. Error message: {$message}");

    http_response_code(500);
    exit;
}

function fatalErrorShutdownHandler()
{
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        // fatal error
        myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

$logger = new Logger('errorlog');
$logger->pushHandler(new StreamHandler(storage_path().'logs/error.log', Logger::DEBUG));

$errorReporter = new ErrorReporter();
$errorReporter->pushHandler(new LoggerErrorHandler($logger));

return $errorReporter;
