<?php

namespace Cordo\Core\UI\Console\Command;

use Exception;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

abstract class BaseConsoleCommand extends Command
{
    protected $container;

    protected $commandBus;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->commandBus = $container->get('command_bus');

        parent::__construct();
    }

    public function getApplication(): Application
    {
        $application = parent::getApplication();

        if (!$application) {
            throw new Exception('Application object hasn\'t been set');
        }

        return $application;
    }
}
