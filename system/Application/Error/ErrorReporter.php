<?php

declare(strict_types=1);

namespace Cordo\Core\Application\Error;

use Exception;
use Throwable;

class ErrorReporter implements ErrorReporterInterface
{
    private $handlers = [];

    private const ERRORS_TO_HANDLE = [
        E_ERROR,
        E_PARSE,
        E_COMPILE_ERROR,
        E_CORE_ERROR,
        E_USER_ERROR,
    ];

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
        } catch (Exception $exception) {
            $this->report($exception);
        }

        http_response_code(500);
        exit;
    }

    public function fatalErrorShutdownHandler(): void
    {
        $lastError = error_get_last();

        if ($lastError && in_array($lastError['type'], self::ERRORS_TO_HANDLE)) {
            $this->errorHandler($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
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
