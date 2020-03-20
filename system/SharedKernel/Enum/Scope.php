<?php

namespace Cordo\Core\SharedKernel\Enum;

use MyCLabs\Enum\Enum;

class Scope extends Enum
{
    private const APP = 'app';

    private const SYSTEM = 'system';

    public static function APP()
    {
        return new self(self::APP);
    }

    public static function SYSTEM()
    {
        return new self(self::SYSTEM);
    }
}
