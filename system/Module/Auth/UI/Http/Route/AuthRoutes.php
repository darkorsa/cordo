<?php

declare(strict_types=1);

namespace System\Module\Auth\UI\Http\Route;

use System\Application\Service\Register\RoutesRegister;

class AuthRoutes extends RoutesRegister
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
            "/{$this->resource}/acl",
            'System\Module\Auth\UI\Http\Controller\UserAclQueriesController@index'
        );
    }

    private function aclUser(): void
    {
        $this->router->addRoute(
            'GET',
            "/{$this->resource}/acl/" . static::UUID_PATTERN,
            'System\Module\Auth\UI\Http\Controller\UserAclQueriesController@get'
        );
    }

    private function aclUserAddRules(): void
    {
        $this->router->addRoute(
            'POST',
            "/{$this->resource}/acl",
            'System\Module\Auth\UI\Http\Controller\UserAclCommandsController@create'
        );
    }

    private function aclUserUpdateRules(): void
    {
        $this->router->addRoute(
            'PUT',
            "/{$this->resource}/acl/" . static::UUID_PATTERN,
            'System\Module\Auth\UI\Http\Controller\UserAclCommandsController@update'
        );
    }

    private function aclUserDeleteRules(): void
    {
        $this->router->addRoute(
            'DELETE',
            "/{$this->resource}/acl/" . static::UUID_PATTERN,
            'System\Module\Auth\UI\Http\Controller\UserAclCommandsController@delete'
        );
    }
}
