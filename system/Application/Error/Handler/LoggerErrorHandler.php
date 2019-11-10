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
        $this->logger->error($exception);
    }
}
