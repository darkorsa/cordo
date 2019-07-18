<?php

namespace App\Auth\UI\Http\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Auth\SharedKernel\Enum\UserRole;
use Psr\Http\Server\MiddlewareInterface;
use App\Auth\Application\Service\AclService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class AclMiddleware implements MiddlewareInterface
{
    private $container;

    private $acl;

    private $service;

    private $privilage;

    public function __construct(ContainerInterface $container, ?string $privilage = null)
    {
        $this->container    = $container;
        $this->acl          = $container->get('acl');
        $this->service      = $container->get(AclService::class);
        $this->privilage    = $privilage;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resource = $this->getResourceFromUriPath($request->getUri()->getPath());
        $priviledge = $this->privilage ?: strtolower($request->getMethod());

        $role = $this->getUserRole($request);
        if ($request->getAttribute('user_id')) {
            $this->service->setUserAclPrivileges($role, $request->getAttribute('user_id'), $this->acl);
        }

        if (!$this->acl->isAllowed($role, $resource, $priviledge)) {
            return new Response(ResponseStatusCode::HTTP_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }

    private function getResourceFromUriPath(string $path): string
    {
        $resource = trim($path, '/');

        if (strpos($resource, '/') !== false) {
            $resource = strstr($resource, '/', true);
        }

        return $resource;
    }

    private function getUserRole(ServerRequestInterface $request): UserRole
    {
        return $request->getAttribute('user_id')
            ? new UserRole(UserRole::LOGGED())
            : new UserRole(UserRole::UNLOGGED());
    }
}
