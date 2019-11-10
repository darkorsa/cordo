<?php

declare(strict_types=1);

namespace System\Application\Service\Loader;

use System\UI\Http\Router;
use Psr\Container\ContainerInterface;

abstract class BaseRoutesLoader
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

    abstract public function load(): void;
}
