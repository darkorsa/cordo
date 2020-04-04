<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\UI\Http\Route;

use Cordo\Core\Application\Service\Register\RoutesRegister;

class AclRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->aclUsers();
        $this->aclUser();
        $this->aclUserAddRules();
        $this->aclUserUpdateRules();
        $this->aclUserDeleteRules();
    }

    private function aclUsers(): void
    {
        $this->router->addRoute(
            'GET',
            "/backoffice-acl",
            'App\Backoffice\Acl\UI\Http\Controller\UserAclQueriesController@index'
        );
    }

    private function aclUser(): void
    {
        $this->router->addRoute(
            'GET',
            "/backoffice-acl/" . static::UUID_PATTERN,
            'App\Backoffice\Acl\UI\Http\Controller\UserAclQueriesController@get'
        );
    }

    private function aclUserAddRules(): void
    {
        $this->router->addRoute(
            'POST',
            "/backoffice-acl",
            'App\Backoffice\Acl\UI\Http\Controller\UserAclCommandsController@create'
        );
    }

    private function aclUserUpdateRules(): void
    {
        $this->router->addRoute(
            'PUT',
            "/backoffice-acl/" . static::UUID_PATTERN,
            'App\Backoffice\Acl\UI\Http\Controller\UserAclCommandsController@update'
        );
    }

    private function aclUserDeleteRules(): void
    {
        $this->router->addRoute(
            'DELETE',
            "/backoffice-acl/" . static::UUID_PATTERN,
            'App\Backoffice\Acl\UI\Http\Controller\UserAclCommandsController@delete'
        );
    }
}
