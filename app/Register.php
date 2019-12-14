<?php

declare(strict_types=1);

namespace App;

use System\Application\Service\Register\ModulesRegister;

class Register extends ModulesRegister
{
    /**
     * Modules register
     *
     * @var array
     */
    protected static $register = [
        'Users',
    ];
}
