<?php

use System\UI\Http\Middleware\OAuth;

$router->addRoute(
    'GET',
    '/welcome',
    'App\UI\Http\Controller\WelcomeController@greet',
    [new OAuth($container)]
);
