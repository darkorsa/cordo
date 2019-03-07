<?php

use System\UI\Http\Middleware\Oauth;

$router->addRoute(
    'GET',
    '/welcome',
    'Turbo\UI\Http\Controller\WelcomeController@greet',
    [Oauth::class]
);
