<?php

namespace Cordo\Core\Application\Service\Register;

use DI\Container;

interface ModuleInit
{
    public static function init(Container $container, bool $isRunningInConsole): void;
}
