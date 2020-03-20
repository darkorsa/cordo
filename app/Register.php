<?php

declare(strict_types=1);

namespace App;

use Cordo\Core\Application\Service\Register\ModulesRegister;

class Register extends ModulesRegister
{
    /**
     * Modules register
     *
     * @var array
     */
    protected static $register = [
        'Backoffice\Users',
        'Backoffice\Auth',
        'Welcome\Message',
    ];
}
