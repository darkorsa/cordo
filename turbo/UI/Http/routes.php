<?php

return function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/welcome', 'Turbo\UI\Http\Controller\WelcomeController@greet');
};
