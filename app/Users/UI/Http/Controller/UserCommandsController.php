<?php

namespace App\Users\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Users\Application\Command\DeleteUser;
use App\Users\Application\Command\UpdateUser;
use System\UI\Http\Controller\BaseController;
use App\Users\Application\Service\UserService;
use App\Users\Application\Command\CreateNewUser;
use App\Users\UI\Validator\UserValidator;

class UserCommandsController extends BaseController
{
    use UserValidator;

    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getParsedBody();

        $result = $this->validate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            (string) Uuid::uuid4(),
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
        $params = $request->getParsedBody();

        $result = $this->validate($params, true);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $user = $this->container->get(UserService::class)->getOneById($userId);

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

        $user = $this->container->get(UserService::class)->getOneById($userId);

        $command = new DeleteUser(
            $user->id(),
            $user->email(),
            $user->password(),
            $user->isActive(),
            $user->createdAt(),
            $user->updatedAt()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }
}
