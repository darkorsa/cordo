<?php

declare(strict_types=1);

namespace Cordo\Core\Application\Service\Register;

use Cordo\Core\UI\Http\Router;
use Psr\Container\ContainerInterface;

abstract class RoutesRegister
{
    protected const UUID_PATTERN = '{id:[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}}';

    protected $router;

    protected $container;

    protected $resource;

    public function __construct(Router $router, ContainerInterface $container, string $resource)
    {
        $this->router = $router;
        $this->container = $container;
        $this->resource = $resource;
    }

    abstract public function register(): void;
}
