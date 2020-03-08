<?php

namespace App\Backoffice\Users\UI\Http\Middleware;

use OAuth2\Request;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OAuthMiddleware implements MiddlewareInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $tokenData = $this->container->get('oauth_server')->getAccessTokenData(Request::createFromGlobals());

        if (!is_array($tokenData) || !array_key_exists('user_id', $tokenData)) {
            $this->container->get('oauth_server')->getResponse()->send();
            die;
        }

        $request = $request->withAttribute('user_id', $tokenData['user_id']);

        return $handler->handle($request);
    }
}
