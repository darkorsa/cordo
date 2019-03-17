<?php declare(strict_types=1);

namespace System\Application\Handler;

use League\Event\EmitterInterface;

abstract class Handler
{
    protected $emitter;
    
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }
}
