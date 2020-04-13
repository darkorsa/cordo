<?php

declare(strict_types=1);

namespace App\Backoffice\Users\UI\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cordo\Core\Application\Query\QueryFilter;
use Cordo\Core\UI\Http\Controller\BaseController;
use App\Backoffice\Users\UI\Transformer\UserTransformer;

class UserQueriesController extends BaseController
{
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $config = $this->container->get('config');
        $queryParams = $request->getQueryParams();

        if (!array_key_exists('offset', $queryParams['page'] ?? [])) {
            $queryParams['page']['offset'] = 0;
        }
        if (!array_key_exists('limit', $queryParams['page'] ?? [])) {
            $queryParams['page']['limit'] = $config->get('backoffice_users::users.limit');
        }

        $userFilter = QueryFilter::fromQueryParams($queryParams);
        $userFilter->addFilter('active', '1');

        $service = $this->container->get('backoffice.users.query.service');

        $data = $this->transformerManager->transform($service->getCollection($userFilter), 'backoffice-users');
        $data['total'] = $service->getCount($userFilter);

        return $this->respondWithData($data);
    }

    public function getAction(ServerRequestInterface $request, $params): ResponseInterface
    {
        $service = $this->container->get('backoffice.users.query.service');

        $userFilter = new QueryFilter();
        $userFilter->addFilter('active', '1');

        $result = $service->getOneById($params['id'], $userFilter);

        return $this->respondWithData($this->transformerManager->transform($result, 'backoffice-users'));
    }

    protected function registerTransformers(): void
    {
        $this->transformerManager->add(new UserTransformer(), 'backoffice-users');
    }
}
