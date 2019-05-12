<?php declare(strict_types=1);

namespace System\Application\Error;

use Exception;
use Throwable;

class ErrorReporter implements ErrorReporterInterface
{
    private $handlers = [];

    public function report(Throwable $exception): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($exception);
        }
    }

    public function pushHandler(ErrorHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function errorHandler($code, $message, $file, $line): void
    {
        $message = "Fatal Error with code {$code} occured in file: {$file} on line {$line}. Error message: {$message}";

        try {
            throw new Exception($message);
        } catch (Exception $e) {
            $this->report($e);
        }

        http_response_code(500);
        exit;
    }

    public function fatalErrorShutdownHandler(): void
    {
        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR) {
            $this->errorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }

    public function exceptionHandler(Throwable $exception): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($exception);
        }

        http_response_code(500);
        exit;
    }
}
