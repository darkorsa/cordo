<?php

namespace App\Auth\UI\Http\Middleware;

use OAuth2\Request;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OAuth implements MiddlewareInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->container->get('oauth_server')->verifyResourceRequest(Request::createFromGlobals())) {
            $this->container->get('oauth_server')->getResponse()->send();
            die;
        }

        $tokenData = $this->container->get('oauth_server')->getAccessTokenData(Request::createFromGlobals());

        $request = $request->withAttribute('user_id', $tokenData['user_id']);

        return $handler->handle($request);
    }
}
