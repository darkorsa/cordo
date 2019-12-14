<?php

use System\Module\Auth\Application\Command\CreateUserAcl;
use System\Module\Auth\Application\Command\DeleteUserAcl;
use System\Module\Auth\Application\Command\UpdateUserAcl;
use System\Module\Auth\Application\Command\Handler\CreateUserAclHandler;
use System\Module\Auth\Application\Command\Handler\DeleteUserAclHandler;
use System\Module\Auth\Application\Command\Handler\UpdateUserAclHandler;

return [
    CreateUserAcl::class    => CreateUserAclHandler::class,
    UpdateUserAcl::class    => UpdateUserAclHandler::class,
    DeleteUserAcl::class    => DeleteUserAclHandler::class,
];
