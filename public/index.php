<?php

/**
 * Handling HTTP request
 */

use App\Register;
use Cordo\Core\UI\Http\Dispatcher;
use Cordo\Core\UI\Http\Response\JsonResponse;
use Cordo\Core\UI\Http\Middleware\ParsePutRequest;

require __DIR__ . '/../bootstrap/autoload.php';

// bootstapping
$container = require_once __DIR__ . '/../bootstrap/app.php';

// init modules
Register::initModules($container, false);

// router
$router = $container->get('router');
$router->addMiddleware(new ParsePutRequest());

// load routes
Register::registerRoutes($router, $container);

// dispatch request
$dispatcher = new Dispatcher(FastRoute\simpleDispatcher($router->routes()), $container);

$response = new JsonResponse($dispatcher->dispatch($container->get('request')));
$response->send();
