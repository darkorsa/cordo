<?php

declare(strict_types=1);

namespace Cordo\Core\Application\Service\Register;

use League\Event\EmitterInterface;
use Psr\Container\ContainerInterface;

abstract class ListenersRegister
{
    protected $emitter;

    protected $container;

    protected $resource;

    public function __construct(EmitterInterface $emitter, ContainerInterface $container, string $resource)
    {
        $this->emitter = $emitter;
        $this->container = $container;
        $this->resource = $resource;
    }

    abstract public function register(): void;
}
