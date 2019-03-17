<?php

namespace System\Application\Event;

use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractListener implements ListenerInterface
{
    protected $commandBus;
    
    public function __construct(ContainerInterface $container)
    {
        $this->commandBus = $container->get('command_bus');
    }
    
    /**
     * @inheritdoc
     */
    public function isListener($listener)
    {
        return $this === $listener;
    }

    abstract public function handle(EventInterface $event);
}
