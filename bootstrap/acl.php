<?php

use App\Loader;
use Zend\Permissions\Acl\Acl;

$acl = new Acl();
Loader::loadResources($acl);

return $acl;
