<?php

declare(strict_types=1);

namespace System\Application\Error\Handler;

use Throwable;
use Psr\Log\LoggerInterface;
use System\Application\Error\ErrorHandlerInterface;

class LoggerErrorHandler implements ErrorHandlerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $exception): void
    {
        $message = sprintf(
            'Error %s occurred in file %s on line %d. Stack trace: ' . PHP_EOL . ' %s',
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        $this->logger->error($message);
    }
}
