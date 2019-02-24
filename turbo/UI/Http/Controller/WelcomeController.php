<?php declare(strict_types=1);

namespace Turbo\UI\Http\Controller;

use GuzzleHttp\Psr7\Response;
use System\UI\Http\Controller\BaseController;

class WelcomeController extends BaseController
{
    public function greetAction(): Response
    {
        return $this->respondWithData('Welcome to the Code Ninjas microframework!');
    }
}
