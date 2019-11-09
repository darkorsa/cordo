<?php

use App\Auth\SharedKernel\Enum\UserRole;
use Zend\Permissions\Acl\Role\GenericRole as Role;

$acl->addRole(new Role(UserRole::GUEST()))
    ->addRole(new Role(UserRole::LOGGED()));
