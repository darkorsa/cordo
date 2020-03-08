<?php

use App\Backoffice\Auth\Application\Command\CreateUserAcl;
use App\Backoffice\Auth\Application\Command\DeleteUserAcl;
use App\Backoffice\Auth\Application\Command\UpdateUserAcl;
use App\Backoffice\Auth\Application\Command\Handler\CreateUserAclHandler;
use App\Backoffice\Auth\Application\Command\Handler\DeleteUserAclHandler;
use App\Backoffice\Auth\Application\Command\Handler\UpdateUserAclHandler;

return [
    CreateUserAcl::class    => CreateUserAclHandler::class,
    UpdateUserAcl::class    => UpdateUserAclHandler::class,
    DeleteUserAcl::class    => DeleteUserAclHandler::class,
];
