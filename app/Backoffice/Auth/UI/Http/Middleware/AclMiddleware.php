<?php

namespace App\Backoffice\Auth\UI\Http\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Cordo\Core\SharedKernel\Enum\SystemRole;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AclMiddleware implements MiddlewareInterface
{
    private const HTTP_UNAUTHORIZED = 401;

    private $container;

    private $acl;

    private $service;

    private $privilage;

    public function __construct(ContainerInterface $container, ?string $privilage = null)
    {
        $this->container    = $container;
        $this->acl          = $container->get('acl');
        $this->service      = $container->get('acl.query.service');
        $this->privilage    = $privilage;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resource = $this->getResourceFromUriPath($request->getUri()->getPath());
        $priviledge = $this->privilage ?: strtolower($request->getMethod());

        $role = $this->getSystemRole($request);
        if ($request->getAttribute('user_id')) {
            $this->service->setUserAclPrivileges($role, $request->getAttribute('user_id'), $this->acl);
        }

        if (!$this->acl->isAllowed($role, $resource, $priviledge)) {
            return new Response(self::HTTP_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }

    private function getResourceFromUriPath(string $path): string
    {
        $resource = trim($path, '/');

        if (strpos($resource, '/') !== false) {
            $resource = strstr($resource, '/', true);
        }

        return (string) $resource;
    }

    private function getSystemRole(ServerRequestInterface $request): SystemRole
    {
        return $request->getAttribute('user_id')
            ? new SystemRole(SystemRole::LOGGED())
            : new SystemRole(SystemRole::GUEST());
    }
}
