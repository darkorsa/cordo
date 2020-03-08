<?php

namespace System\SharedKernel\Enum;

use MyCLabs\Enum\Enum;

class SystemRole extends Enum
{
    private const GUEST = 'guest';

    private const LOGGED = 'logged';

    public static function GUEST()
    {
        return new self(self::GUEST);
    }

    public static function LOGGED()
    {
        return new self(self::LOGGED);
    }
}
