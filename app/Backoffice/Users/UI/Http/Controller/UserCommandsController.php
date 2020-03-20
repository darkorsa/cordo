<?php

namespace App\Backoffice\Users\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cordo\Core\UI\Http\Controller\BaseController;
use App\Backoffice\Users\UI\Validator\NewUserValidator;
use App\Backoffice\Users\Application\Command\DeleteUser;
use App\Backoffice\Users\Application\Command\UpdateUser;
use App\Backoffice\Users\UI\Validator\UpdateUserValidator;
use App\Backoffice\Users\Application\Command\CreateNewUser;
use App\Backoffice\Users\UI\Validator\EmailExistsValidation;

class UserCommandsController extends BaseController
{
    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = (array) $request->getParsedBody();
        $service = $this->container->get('users.query.service');

        $validator = new NewUserValidator($params);
        $validator->addCallbackValidator('email', new EmailExistsValidation($service));

        if (!$validator->isValid()) {
            return $this->respondBadRequestError($validator->messages());
        }

        $params = (object) $params;

        $command = new CreateNewUser(
            (string) $params->email,
            (string) $params->password,
            new DateTime()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    public function updateAction(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('user_id');
        $params = (array) $request->getParsedBody();

        $validator = new UpdateUserValidator($params);

        if (!$validator->isValid()) {
            return $this->respondBadRequestError($validator->messages());
        }

        $user = $this->container->get('users.query.service')->getOneById($userId);

        $params = (object) $params;

        $command = new UpdateUser(
            $user->id(),
            (string) $params->email,
            (bool) $params->active,
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
