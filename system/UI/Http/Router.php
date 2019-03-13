<?php declare(strict_types=1);

namespace System\UI\Http;

use stdClass;
use Relay\Relay;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router
{
    private $middlewares = [];
    
    private $routes = [];

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function addRoute(string $method, string $path, $handler, array $middlewares = []): void
    {
        $route = new stdClass();
        $route->method      = $method;
        $route->path        = $path;
        $route->handler     = $handler;
        $route->middlewares = $this->middlewares + $middlewares;
        
        $this->routes[] = $route;
    }
    
    public function routes()
    {
        return function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute($route->method, $route->path, $this->getHandler($route));
            }
        };
    }

    private function getHandler(stdClass $route)
    {
        return function (ServerRequestInterface $request, ContainerInterface $container) use ($route) {
            $route->middlewares[] = function (
                RequestInterface $request,
                RequestHandlerInterface $handler
            ) use (
                $route,
                $container
            ) {
                if (is_callable($route->handler)) {
                    $handlerCallable = $route->handler;
                    
                    return $handlerCallable();
                }

                [$controller, $action] = explode('@', $route->handler);

                return $container->get($controller)->{$action.'Action'}($request);
            };

            return $this->processMiddlewares($route->middlewares, $request);
        };
    }

    private function processMiddlewares(array $middlewares, ServerRequestInterface $request): ResponseInterface
    {
        $relay = new Relay($middlewares, function ($entry) {
            return is_string($entry) ? new $entry() : $entry;
        });
        
        return $relay->handle($request);
    }
}
