<?php

/**
 * Handling HTTP request
 */

use App\Loader;
use Monolog\Logger;
use System\UI\Http\Dispatcher;
use Monolog\Handler\StreamHandler;
use System\UI\Http\Response\JsonResponse;
use System\UI\Http\Middleware\ParsePutRequest;

require __DIR__.'/../bootstrap/autoload.php';

$logger = new Logger('logger');
$logger->pushHandler(new StreamHandler(storage_path().'logs/error.log', Logger::ERROR));

try {
    // bootstapping
    $container = require_once __DIR__.'/../bootstrap/app.php';

    // oauth
    $oauthServer = require_once __DIR__.'/../bootstrap/oauth.php';
    $container->set('oauth_server', $oauthServer);

    // router
    $router = $container->get('router');
    $router->addMiddleware(new ParsePutRequest());

    Loader::loadRoutes($router, $container);

    // dispatch request
    $dispatcher = new Dispatcher(FastRoute\simpleDispatcher($router->routes()), $container);

    $response = new JsonResponse($dispatcher->dispatch($container->get('request')));
    $response->send();
} catch (Exception $e) {
    $logger->error($e);
    http_response_code(500);
    exit;
}
