<?php declare(strict_types=1);

namespace App;

use System\Application\Loader as BaseLoader;

class Loader extends BaseLoader
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
