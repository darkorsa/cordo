<?php

namespace App\Users\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Users\Application\Command\DeleteUser;
use App\Users\Application\Command\UpdateUser;
use System\UI\Http\Controller\BaseController;
use App\Users\Application\Command\CreateNewUser;
use App\Users\UI\Validator\UserValidator;

class UserCommandsController extends BaseController
{
    use UserValidator;

    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = (array) $request->getParsedBody();

        $result = $this->validateNewUser($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            Uuid::uuid4()->toString(),
            (string) $params->email,
            (string) $params->password,
            (int) false,
            new DateTime(),
            new DateTime()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    public function updateAction(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('user_id');
        $params = (array) $request->getParsedBody();

        $result = $this->validateUserUpdate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $user = $this->container->get('users.query.service')->getOneById($userId);

        $params = (object) $params;

        $command = new UpdateUser(
            $user->id(),
            (string) $params->email,
            $user->password(),
            $user->isActive(),
            $user->createdAt(),
            new DateTime()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    public function deleteAction(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('user_id');

        $user = $this->container->get('users.query.service')->getOneById($userId);

        $command = new DeleteUser($user->id());

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }
}
