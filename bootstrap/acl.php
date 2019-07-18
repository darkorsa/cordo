<?php

use App\Loader;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

$acl = new Acl();
$acl->addRole(new Role('loggedUser'));
$acl->addRole(new Role('unloggedUser'));

Loader::loadResources($acl);

return $acl;
