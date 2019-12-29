<?php

declare(strict_types=1);

namespace System\Module\Welcome\UI\Http\Route;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use System\Application\Service\Register\RoutesRegister;

class WelcomeRoutes extends RoutesRegister
{
    public function register(): void
    {
        $this->router->addRoute(
            'GET',
            "/",
            function (ServerRequestInterface $request, array $params) {
                return new Response(200, [], (string) json_encode([
                    'response' => 'Welcome to Cordo microframework & happy API development!'
                ]));
            }
        );
    }
}
