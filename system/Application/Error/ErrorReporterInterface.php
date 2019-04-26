<?php declare(strict_types=1);

namespace System\Application\Error;

use Throwable;
use System\Application\Error\ErrorHandlerInterface;

interface ErrorReporterInterface
{
    public function report(Throwable $exception): void;

    public function pushHandler(ErrorHandlerInterface $handler): void;
}
