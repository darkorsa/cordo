<?php

declare(strict_types=1);

namespace App\Auth\UI\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use App\Auth\Application\Query\AclFilter;
use App\Auth\UI\Transformer\AclTransformer;
use Psr\Http\Message\ServerRequestInterface;
use System\UI\Http\Controller\BaseController;

class UserAclQueriesController extends BaseController
{
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $aclFilter = new AclFilter();
        if (array_key_exists('id_user', $queryParams)) {
            $aclFilter->setUserId($queryParams['id_user']);
        }

        $service = $this->container->get('acl.query.service');

        $data = $this->transformerManager->transform($service->getCollection($aclFilter), 'acl');
        $data['total'] = $service->getCount($aclFilter);

        return $this->respondWithData($data);
    }

    public function getAction(ServerRequestInterface $request, $params): ResponseInterface
    {
        $service = $this->container->get('acl.query.service');

        $result = $service->getOneById($params['id']);

        return $this->respondWithData($this->transformerManager->transform($result, 'acl'));
    }

    protected function registerTransformers(): void
    {
        $this->transformerManager->add(new AclTransformer(), 'acl');
    }
}
