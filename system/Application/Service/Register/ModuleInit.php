<?php

namespace System\Application\Service\Register;

use DI\Container;

interface ModuleInit
{
    public static function init(Container $container, bool $isConsole): void;
}
