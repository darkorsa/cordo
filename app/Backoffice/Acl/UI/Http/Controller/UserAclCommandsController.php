<?php

namespace App\Backoffice\Acl\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Particle\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Particle\Validator\ValidationResult;
use Psr\Http\Message\ServerRequestInterface;
use Cordo\Core\UI\Http\Controller\BaseController;
use App\Backoffice\Acl\Application\Command\CreateUserAcl;
use App\Backoffice\Acl\Application\Command\DeleteUserAcl;
use App\Backoffice\Acl\Application\Command\UpdateUserAcl;
use Particle\Validator\Exception\InvalidValueException;
use Cordo\Core\Application\Exception\ResourceNotFoundException;

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

        $user = $this->container->get('backoffice.users.query.service')->getOneById($params->id_user);

        $command = new CreateUserAcl(
            Uuid::uuid1()->toString(),
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
        $params['id'] = $urlParams['id'];

        $result = $this->validateUpdate($params);

        if (!$result->isValid()) {
            return $this->respondBadRequestError($result->getMessages());
        }

        $params = (object) $params;

        $acl    = $this->container->get('backoffice.acl.query.service')->getOneById($params->id);
        $user   = $this->container->get('backoffice.users.query.service')->getOneById($acl->userId());

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
        $id = $urlParams['id'];

        $command = new DeleteUserAcl($id);

        $this->commandBus->handle($command);

        return $this->respondWithSuccess();
    }

    private function validateCreate(array $params): ValidationResult
    {
        $service = $this->container->get('backoffice.acl.query.service');

        $validator = new Validator();
        $validator->required('privileges')->isArray();
        $validator->required('id_user')
            ->uuid(Uuid::UUID_TYPE_RANDOM)
            ->callback(static function ($value) use ($service) {
                try {
                    $service->getOneByUserId($value);
                    throw new InvalidValueException('Acl definitions are unique per user', 'Unique::USER_NOT_UNIQUE');
                } catch (ResourceNotFoundException $exeption) {
                    return true;
                }
            });

        return $validator->validate($params);
    }

    private function validateUpdate(array $params): ValidationResult
    {
        $validator = new Validator();
        $validator->required('privileges')->isArray();

        return $validator->validate($params);
    }
}
