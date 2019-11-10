<?php

namespace System\SharedKernel\Enum;

use MyCLabs\Enum\Enum;

class Env extends Enum
{
    private const DEV = 'dev';

    private const PRODUCTION = 'production';

    public static function DEV()
    {
        return new self(self::DEV);
    }

    public static function PRODUCTION()
    {
        return new self(self::PRODUCTION);
    }
}
