<?php

/**
 * Handling HTTP request
 */

use App\Register;
use GuzzleHttp\Psr7\Response;
use Cordo\Core\UI\Http\Dispatcher;
use Tuupola\Middleware\CorsMiddleware;
use Cordo\Core\UI\Http\Response\JsonResponse;
use Cordo\Core\UI\Http\Middleware\ParsePutRequest;
use Cordo\Core\UI\Http\Middleware\OAuth2ServerMiddleware;

require __DIR__ . '/../bootstrap/autoload.php';

// bootstapping
$container = require_once __DIR__ . '/../bootstrap/app.php';

// router
$router = $container->get('router');
$router->addMiddleware(new CorsMiddleware($container->get('config')->get('cors')));
$router->addMiddleware(new ParsePutRequest());
$router->addMiddleware(new OAuth2ServerMiddleware(
    $container,
    $container->get('config')->get('auth'),
    $container->get('db_config')
));
$router->addRoute(
    'OPTIONS',
    "/{endpoint:.+}",
    function () {
        return new Response();
    }
);

// load routes
Register::registerRoutes($router, $container);

// dispatch request
$dispatcher = new Dispatcher(FastRoute\simpleDispatcher($router->routes()), $container);

$response = new JsonResponse($dispatcher->dispatch($container->get('request')));
$response->send();
