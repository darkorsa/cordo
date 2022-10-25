<?php

use GuzzleHttp\Psr7\Response;
use Cordo\Core\UI\Http\Dispatcher;
use Tuupola\Middleware\CorsMiddleware;
use Cordo\Core\UI\Http\Response\JsonResponse;
use Cordo\Core\UI\Http\Middleware\ParsePutRequest;
use Cordo\Core\UI\Http\Middleware\OAuth2ServerMiddleware;

require __DIR__ . '/../bootstrap/autoload.php';

// bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';

// router
$router = $app->router;
$router->addMiddleware(new CorsMiddleware($app->config->get('cors')));
$router->addMiddleware(new ParsePutRequest());
$router->addMiddleware(new OAuth2ServerMiddleware(
    $app->container,
    $app->config->get('auth'),
    $app->db_config
));
$router->addRoute(
    'OPTIONS',
    "/{endpoint:.+}",
    function () {
        return new Response();
    }
);

# register services and modules
$app->register();

// dispatch request
$dispatcher = new Dispatcher(FastRoute\simpleDispatcher($router->routes()), $app->container);

$response = new JsonResponse($dispatcher->dispatch($app->request));
$response->send();
