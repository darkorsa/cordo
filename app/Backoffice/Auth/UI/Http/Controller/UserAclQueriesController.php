<?php

declare(strict_types=1);

namespace App\Backoffice\Auth\UI\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use App\Backoffice\Auth\Application\Query\AclFilter;
use App\Backoffice\Auth\UI\Transformer\AclTransformer;
use Psr\Http\Message\ServerRequestInterface;
use Cordo\Core\UI\Http\Controller\BaseController;

class UserAclQueriesController extends BaseController
{
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $aclFilter = new AclFilter();
        if (array_key_exists('id_user', $queryParams)) {
            $aclFilter->setUserId($queryParams['id_user']);
        }

        $service = $this->container->get('backoffice.acl.query.service');

        $data = $this->transformerManager->transform($service->getCollection($aclFilter), 'backoffice-acl');
        $data['total'] = $service->getCount($aclFilter);

        return $this->respondWithData($data);
    }

    public function getAction(ServerRequestInterface $request, $params): ResponseInterface
    {
        $service = $this->container->get('backoffice.acl.query.service');

        $result = $service->getOneById($params['id']);

        return $this->respondWithData($this->transformerManager->transform($result, 'backoffice-acl'));
    }

    protected function registerTransformers(): void
    {
        $this->transformerManager->add(new AclTransformer(), 'backoffice-acl');
    }
}
