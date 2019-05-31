<?php

namespace App\Users\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Particle\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Particle\Validator\ValidationResult;
use Psr\Http\Message\ServerRequestInterface;
use App\Users\Application\Command\DeleteUser;
use App\Users\Application\Command\UpdateUser;
use System\UI\Http\Controller\BaseController;
use App\Users\Application\Service\UserService;
use App\Users\Application\Command\CreateNewUser;
use Particle\Validator\Exception\InvalidValueException;
use System\Application\Exception\ResourceNotFoundException;

class UserCommandsController extends BaseController
{
    const EMAIL_MAX_LENGTH = 50;

    const PASSWORD_MIN_LENGTH = 6;

    const PASSWORD_MAX_LENGTH = 18;

    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = (array) $request->getParsedBody();

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

        $params = (array) $request->getParsedBody();

        $result = $this->validate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $user = $this->container->get(UserService::class)->getOneById($userId);

        $params = (object) $params;

        $command = new UpdateUser(
            $user->id(),
            (string) $params->email,
            (string) $params->password,
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
            (string) 'testowe3',
            $user->isActive(),
            $user->createdAt(),
            $user->updatedAt()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    private function validate(array $params): ValidationResult
    {
        $service = $this->container->get(UserService::class);
        
        $validator = new Validator;
        $validator->required('password')->lengthBetween(self::PASSWORD_MIN_LENGTH, self::PASSWORD_MAX_LENGTH);
        $validator->required('email')
            ->lessThan(self::EMAIL_MAX_LENGTH)
            ->email()
            ->callback(function ($value) use ($service) {
                try {
                    $service->getOneByEmail($value);
                    throw new InvalidValueException('Email address us not unique', 'Unique::EMAIL_NOT_UNIQUE');
                } catch (ResourceNotFoundException $ex) {
                    return true;
                }
            });

        return $validator->validate($params);
    }
}
