<?php

namespace App\Auth\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Particle\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Particle\Validator\ValidationResult;
use App\Auth\Application\Service\AclService;
use Psr\Http\Message\ServerRequestInterface;
use System\UI\Http\Controller\BaseController;
use App\Users\Application\Service\UserService;
use App\Auth\Application\Command\CreateUserAcl;
use App\Auth\Application\Command\DeleteUserAcl;
use App\Auth\Application\Command\UpdateUserAcl;
use Particle\Validator\Exception\InvalidValueException;
use System\Application\Exception\ResourceNotFoundException;

class UserAclCommandsController extends BaseController
{
    public function createAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = (array) $request->getParsedBody();

        $result = $this->validateCreate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $params = (object) $params;

        $user = $this->container->get(UserService::class)->getOneById($params->id_user);

        $command = new CreateUserAcl(
            (string) Uuid::uuid4(),
            $user,
            (array) $params->privileges,
            new DateTime(),
            new DateTime()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    public function updateAction(ServerRequestInterface $request, array $urlParams): ResponseInterface
    {
        $params = (array) $request->getParsedBody();
        $params['id_user'] = $urlParams['id'];

        $result = $this->validateUpdate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $params = (object) $params;

        $user   = $this->container->get(UserService::class)->getOneById($params->id_user);
        $acl    = $this->container->get(AclService::class)->getOneByUserId($params->id_user);

        $command = new UpdateUserAcl(
            $acl->id(),
            $user,
            (array) $params->privileges,
            $acl->createdAt(),
            new DateTime()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    public function deleteAction(ServerRequestInterface $request, array $urlParams): ResponseInterface
    {
        $userId = $urlParams['id'];

        $user   = $this->container->get(UserService::class)->getOneById($userId);
        $acl    = $this->container->get(AclService::class)->getOneByUserId($userId);

        $command = new DeleteUserAcl(
            $acl->id(),
            $user,
            $acl->privileges(),
            $acl->createdAt(),
            $acl->updatedAt()
        );

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    private function validateCreate(array $params): ValidationResult
    {
        $service = $this->container->get(AclService::class);

        $validator = new Validator;
        $validator->required('privileges')->isArray();
        $validator->required('id_user')
            ->uuid(Uuid::UUID_TYPE_RANDOM)
            ->callback(function ($value) use ($service) {
                try {
                    $service->getOneByUserId($value);
                    throw new InvalidValueException('Acl definitions are unique per user', 'Unique::USER_NOT_UNIQUE');
                } catch (ResourceNotFoundException $ex) {
                    return true;
                }
            });

        return $validator->validate($params);
    }

    private function validateUpdate(array $params): ValidationResult
    {
        $validator = new Validator;
        $validator->required('privileges')->isArray();

        return $validator->validate($params);
    }
}
