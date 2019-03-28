<?php

namespace System\UI\Console\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

abstract class BaseConsoleCommand extends Command
{
    protected $container;

    protected $commandBus;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container            = $container;
        $this->commandBus           = $container->get('command_bus');

        parent::__construct();
    }
}
