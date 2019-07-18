<?php

namespace App\Auth\SharedKernel\Enum;

use MyCLabs\Enum\Enum;

class UserRole extends Enum
{
    private const LOGGED = 'loggedUser';
    
    private const UNLOGGED = 'unloggedUser';
}