<?php declare(strict_types=1);

use System\UI\Http\Dispatcher;
use System\UI\Http\Response as HttpResponse;

require __DIR__.'/../bootstrap/autoload.php';

$container = require_once __DIR__.'/../bootstrap/app.php';

/**
 * Dispatcher
 */
$simpleDispatcher = FastRoute\simpleDispatcher(require_once(app_path() . 'UI/Http/routes.php'));

$dispatcher = new Dispatcher($simpleDispatcher, $container);

$dispatherResponse = $dispatcher->dispatch('GET', '/classify');
$httpResponse = new HttpResponse($dispatherResponse);
$httpResponse->json();
