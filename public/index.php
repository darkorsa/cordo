<?php

/**
 * Handling HTTP request
 */

use App\Loader;
use Whoops\Run;
use System\UI\Http\Dispatcher;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use System\UI\Http\Response\JsonResponse;
use System\UI\Http\Middleware\ParsePutRequest;
use System\Application\Error\Handler\EmailErrorHandler;
use System\Application\Error\Handler\PrettyErrorHandler;

require __DIR__.'/../bootstrap/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(root_path().'.env');

$debug = getenv('APP_DEBUG') == 'true';

// pretty errors
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);

$errorReporter = require_once __DIR__.'/../bootstrap/error.php';

if (!$debug) {
    $errorReporter->pushHandler(new PrettyErrorHandler($whoops));
} else {
    $errorReporter->pushHandler(new EmailErrorHandler());
}

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
} catch (Error | Exception $e) {
    $errorReporter->report($e);
    http_response_code(500);
    exit;
}
