<?php declare(strict_types=1);

use System\UI\Http\Dispatcher;
use System\UI\Http\Response\JsonResponse;

require __DIR__.'/../bootstrap/autoload.php';

// di container
$container = require_once __DIR__.'/../bootstrap/app.php';

// oauth
$oauthServer = require_once __DIR__.'/../bootstrap/oauth.php';
$container->set('oauth_server', $oauthServer);

// router
$router = $container->get('router');

require_once app_path() . 'UI/Http/routes.php';
require_once app_path() . 'UI/Http/routes-auth.php';

// dispatch request
$dispatcher = new Dispatcher(FastRoute\simpleDispatcher($router->routes()), $container);

$response = new JsonResponse($dispatcher->dispatch($container->get('request')));
$response->send();
