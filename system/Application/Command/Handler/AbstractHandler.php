<?php declare(strict_types=1);

namespace System\Application\Command\Handler;

use League\Event\EmitterInterface;

abstract class AbstractHandler
{
    protected $emitter;
    
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }
}
