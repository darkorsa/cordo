<?php

use OAuth2\Request;

$router->addRoute(
    'POST',
    '/token',
    static function () use ($container) {
        $request = Request::createFromGlobals();

        $response = $container->get('oauth_server')->handleTokenRequest($request);

        if ($response->getStatusText() === 'OK') {
            $response->setParameter('login', $request->request('username'));
        }

        $response->send();
        die;
    }
);

$router->addRoute(
    'POST',
    '/token-refresh',
    static function () use ($container) {
        $response = $container->get('oauth_server')->handleTokenRequest(Request::createFromGlobals());

        $response->send();
        die;
    }
);

$router->addRoute(
    'GET',
    "/{$resource}/acl/users",
    'App\Auth\UI\Http\Controller\UserAclQueriesController@index'
);

$router->addRoute(
    'GET',
    "/{$resource}/acl/users/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}",
    'App\Auth\UI\Http\Controller\UserAclQueriesController@get'
);

$router->addRoute(
    'POST',
    "/{$resource}/acl/users",
    'App\Auth\UI\Http\Controller\UserAclCommandsController@create'
);

$router->addRoute(
    'PUT',
    "/{$resource}/acl/users/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}",
    'App\Auth\UI\Http\Controller\UserAclCommandsController@update'
);

$router->addRoute(
    'DELETE',
    "/{$resource}/acl/users/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}",
    'App\Auth\UI\Http\Controller\UserAclCommandsController@delete'
);
