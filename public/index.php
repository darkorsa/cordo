<?php declare(strict_types=1);

use System\UI\Http\Dispatcher;
use System\UI\Http\Response\JsonResponse;

require __DIR__.'/../bootstrap/autoload.php';

$container = require_once __DIR__.'/../bootstrap/app.php';

/**
 * Dispatch request
 */
$router = FastRoute\simpleDispatcher(require_once(app_path() . 'UI/Http/routes.php'));

$dispatcher = new Dispatcher($router, $container);

$response = new JsonResponse($dispatcher->dispatch($container->get('request')));
$response->send();
