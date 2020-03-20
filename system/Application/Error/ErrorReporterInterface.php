<?php

declare(strict_types=1);

namespace Cordo\Core\Application\Error;

use Throwable;

interface ErrorReporterInterface
{
    public function report(Throwable $exception): void;

    public function pushHandler(ErrorHandlerInterface $handler): void;
}
