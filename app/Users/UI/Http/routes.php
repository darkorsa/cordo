<?php

use App\Auth\UI\Http\Middleware\OAuth;

$router->addRoute(
    'GET',
    '/users',
    'App\Users\UI\Http\Controller\UserQueriesController@index'
);

$router->addRoute(
    'GET',
    '/users/{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}',
    'App\Users\UI\Http\Controller\UserQueriesController@get'
    //[new OAuth($container)]
);

$router->addRoute(
    'PUT',
    '/users',
    'App\Users\UI\Http\Controller\UserCommandsController@create'
);
