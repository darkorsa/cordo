<?php

use App\Register;
use Zend\Permissions\Acl\Acl;
use App\Auth\SharedKernel\Enum\SystemRole;
use Zend\Permissions\Acl\Role\GenericRole as Role;

$acl = new Acl();
// add system roles
$acl->addRole(new Role(SystemRole::GUEST()))
    ->addRole(new Role(SystemRole::LOGGED()));

Register::registerAclData($acl);

return $acl;
