<?php

declare(strict_types=1);

namespace Cordo\Core\Module\Welcome\Message\UI\Http\Route;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Cordo\Core\Application\Service\Register\RoutesRegister;

class MessageRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->router->addRoute(
            'GET',
            "/",
            function (ServerRequestInterface $request, array $params) {
                return new Response(200, [], (string) json_encode([
                    'response' => 'Welcome to Cordo microframework. Enjoy your API development!'
                ]));
            }
        );
    }
}
