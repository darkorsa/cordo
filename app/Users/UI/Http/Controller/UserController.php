<?php declare(strict_types=1);

namespace App\Users\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Particle\Validator\Validator;
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
        $params = $request->getParsedBody();

        $validator = new Validator;
        $validator->required('email')->lengthBetween(6, 50)->email();
        $validator->required('password')->lengthBetween(6, 18);

        $result = $validator->validate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            (string) Uuid::uuid4(),
            (string) $params->email,
            (string) $params->password,
            (int) false,
            new DateTime()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }
}
