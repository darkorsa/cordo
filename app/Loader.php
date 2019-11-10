<?php

declare(strict_types=1);

namespace App;

use System\Application\Service\Loader\ModulesLoader;

class Loader extends ModulesLoader
{
    /**
     * Modules register
     *
     * @var array
     */
    protected static $register = [
        'Auth',
        'Users',
    ];
}
