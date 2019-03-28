<?php declare(strict_types=1);

namespace App\Users\UI\Http\Controller;

use DateTime;
use Ramsey\Uuid\Uuid;
use Particle\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use App\Users\Application\Query\UserFilter;
use Psr\Http\Message\ServerRequestInterface;
use App\Users\UI\Transformer\UserTransformer;
use System\UI\Http\Controller\BaseController;
use App\Users\Application\Service\UserService;
use App\Users\Application\Command\CreateNewUser;

class UserController extends BaseController
{
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $config = $this->container->get('config');

        $userFilter = new UserFilter();
        $userFilter
            ->setActive(true)
            ->setOffset((int) ($queryParams['offset'] ?? 0))
            ->setLimit((int) ($queryParams['limit'] ?? $config->get('users.limit')));
        
        $service = $this->container->get(UserService::class);

        $data = $this->transformerManager->transform($service->getCollection($userFilter), 'user');
        $data['total'] = $service->getCount($userFilter);

        return $this->respondWithData($data);
    }
    
    public function getAction(ServerRequestInterface $request, $params): ResponseInterface
    {
        $service = $this->container->get(UserService::class);

        $userFilter = new UserFilter();
        $userFilter->setActive(true);

        $result = $service->getOneById($params['id'], $userFilter);

        return $this->respondWithData($this->transformerManager->transform($result, 'user'));
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

    protected function registerTransformers(): void
    {
        $this->transformerManager->add(new UserTransformer(), 'user');
    }
}
