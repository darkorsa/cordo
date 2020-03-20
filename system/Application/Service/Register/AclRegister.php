<?php

declare(strict_types=1);

namespace Cordo\Core\Application\Service\Register;

use Laminas\Permissions\Acl\Acl;

abstract class AclRegister
{
    protected $acl;

    protected $resource;

    public function __construct(Acl $acl, string $resource)
    {
        $this->acl = $acl;
        $this->resource = $resource;
    }

    abstract public function register(): void;
}
