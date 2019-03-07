<?php declare(strict_types=1);

namespace Turbo\UI\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use System\UI\Http\Controller\BaseController;

class WelcomeController extends BaseController
{
    public function greetAction(ServerRequestInterface $request): ResponseInterface
    {
        dd($request->getQueryParams());
        
        return $this->respondWithData('Welcome to the Code Ninjas microframework!');
    }
}
