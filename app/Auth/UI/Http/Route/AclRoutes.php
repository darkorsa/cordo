<?php

declare(strict_types=1);

namespace App\Auth\UI\Http\Route;

use System\Application\Service\Register\RoutesRegister;

class AclRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->addAclUsers();
        $this->addAclUser();
        $this->addAclUserAddRules();
        $this->addAclUserUpdateRules();
        $this->addAclUserDeleteRules();
    }

    private function addAclUsers(): void
    {
        $this->router->addRoute(
            'GET',
            "/{$this->resource}/acl/users",
            'App\Auth\UI\Http\Controller\UserAclQueriesController@index'
        );
    }

    private function addAclUser(): void
    {
        $this->router->addRoute(
            'GET',
            "/{$this->resource}/acl/users/" . static::UUID_PATTERN,
            'App\Auth\UI\Http\Controller\UserAclQueriesController@get'
        );
    }

    private function addAclUserAddRules(): void
    {
        $this->router->addRoute(
            'POST',
            "/{$this->resource}/acl/users",
            'App\Auth\UI\Http\Controller\UserAclCommandsController@create'
        );
    }

    private function addAclUserUpdateRules(): void
    {
        $this->router->addRoute(
            'PUT',
            "/{$this->resource}/acl/users/" . static::UUID_PATTERN,
            'App\Auth\UI\Http\Controller\UserAclCommandsController@update'
        );
    }

    private function addAclUserDeleteRules(): void
    {
        $this->router->addRoute(
            'DELETE',
            "/{$this->resource}/acl/users/" . static::UUID_PATTERN,
            'App\Auth\UI\Http\Controller\UserAclCommandsController@delete'
        );
    }
}
