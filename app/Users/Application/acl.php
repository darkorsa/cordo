<?php

use App\Auth\SharedKernel\Enum\UserRole;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

$acl->addResource(new Resource($resource));

$acl->allow(UserRole::GUEST(), $resource);
$acl->allow(UserRole::LOGGED(), $resource);
