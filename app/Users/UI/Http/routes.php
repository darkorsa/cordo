<?php

use App\Auth\UI\Http\Middleware\OAuth;

$router->addRoute(
    'GET',
    '/users',
    'App\Users\UI\Http\Controller\UserController@greet',
    [new OAuth($container)]
);

$router->addRoute(
    'PUT',
    '/users',
    'App\Users\UI\Http\Controller\UserController@create'
);
