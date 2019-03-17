<?php declare(strict_types=1);

namespace App\Users\UI\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use System\UI\Http\Controller\BaseController;
use App\Users\Application\Command\CreateNewUser;

class UserController extends BaseController
{
    public function greetAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->respondWithData('Welcome to the Code Ninjas microframework!');
    }

    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = (object) $request->getParsedBody();
        
        $command = new CreateNewUser(
            (string) $params->email,
            (string) $params->password
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }
}
