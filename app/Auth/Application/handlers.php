<?php

use App\Auth\Application\Command\CreateUserAcl;
use App\Auth\Application\Command\DeleteUserAcl;
use App\Auth\Application\Command\UpdateUserAcl;
use App\Auth\Application\Command\Handler\CreateUserAclHandler;
use App\Auth\Application\Command\Handler\DeleteUserAclHandler;
use App\Auth\Application\Command\Handler\UpdateUserAclHandler;

return [
    CreateUserAcl::class    => CreateUserAclHandler::class,
    UpdateUserAcl::class    => UpdateUserAclHandler::class,
    DeleteUserAcl::class    => DeleteUserAclHandler::class,
];
