<?php declare(strict_types=1);

namespace System\Application\Error;

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

    public function pushHandler(ErrorHandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }
}
