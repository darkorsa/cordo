<?php declare(strict_types=1);

namespace System\UI\Http;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use FastRoute\Dispatcher as FRDispatcher;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher
{
    private $dispatcher;

    private $container;
    
    public function __construct(
        FRDispatcher $dispatcher,
        ContainerInterface $container
    ) {
        $this->dispatcher   = $dispatcher;
        $this->container    = $container;
    }

    public function dispatch(ServerRequestInterface $request): Response
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeInfo[0]) {
            case FRDispatcher::NOT_FOUND:
                return new Response(404);
            case FRDispatcher::METHOD_NOT_ALLOWED:
                return new Response(404);
            case FRDispatcher::FOUND:
                list($state, $handler, $vars) = $routeInfo;
                list($class, $method) = explode('@', $handler, 2);

                $controller = $this->container->get($class);
                //$controller->setRequest($request);

                return $controller->run($method, ...array_values($vars));
        }
    }
}
