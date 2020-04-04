<?php

use App\Backoffice\Acl\Application\Command\CreateUserAcl;
use App\Backoffice\Acl\Application\Command\DeleteUserAcl;
use App\Backoffice\Acl\Application\Command\UpdateUserAcl;
use App\Backoffice\Acl\Application\Command\Handler\CreateUserAclHandler;
use App\Backoffice\Acl\Application\Command\Handler\DeleteUserAclHandler;
use App\Backoffice\Acl\Application\Command\Handler\UpdateUserAclHandler;

return [
    CreateUserAcl::class    => CreateUserAclHandler::class,
    UpdateUserAcl::class    => UpdateUserAclHandler::class,
    DeleteUserAcl::class    => DeleteUserAclHandler::class,
];
