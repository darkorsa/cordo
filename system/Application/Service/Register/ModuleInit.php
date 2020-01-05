<?php

namespace System\Application\Service\Register;

use Psr\Container\ContainerInterface;

interface ModuleInit
{
    public static function init(ContainerInterface $container, bool $isConsole): void;
}
