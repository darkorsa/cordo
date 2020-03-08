<?php

use App\Register;
use Laminas\Permissions\Acl\Acl;
use System\SharedKernel\Enum\SystemRole;
use Laminas\Permissions\Acl\Role\GenericRole as Role;

$acl = new Acl();
// add system roles
$acl->addRole(new Role(SystemRole::GUEST()))
    ->addRole(new Role(SystemRole::LOGGED()));

Register::registerAclData($acl);

return $acl;
