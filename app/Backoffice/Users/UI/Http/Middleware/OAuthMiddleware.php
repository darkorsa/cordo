<?php

namespace App\Backoffice\Users\UI\Http\Middleware;

use OAuth2\Request;
use OAuth2\Response;
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
        $config = $this->container->get('config');
        $server = $this->container->get('backoffice_oauth_server');
        $response = new Response();

        if (!$server->verifyResourceRequest(
            Request::createFromGlobals(),
            $response,
            $config->get('backoffice_users::oauth.scope')
        )) {
            // if the scope required is different from what the token allows,
            // this will send a "401 insufficient_scope" error
            $response->send();
            die;
        }

        $tokenData = (array) $server->getAccessTokenData(Request::createFromGlobals());

        if (!array_key_exists('user_id', $tokenData)) {
            $response->send();
            die;
        }

        $request = $request->withAttribute('user_id', $tokenData['user_id']);

        return $handler->handle($request);
    }
}
