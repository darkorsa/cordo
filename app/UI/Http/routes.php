<?php

use System\UI\Http\Middleware\Oauth;

$router->addRoute(
    'GET',
    '/welcome',
    'App\UI\Http\Controller\WelcomeController@greet',
    [Oauth::class]
);
