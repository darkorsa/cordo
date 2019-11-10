<?php

declare(strict_types=1);

namespace System\Application\Service\Loader;

use Zend\Permissions\Acl\Acl;

abstract class BaseAclLoader
{
    protected $acl;

    protected $resource;

    public function __construct(Acl $acl, string $resource)
    {
        $this->acl = $acl;
        $this->resource = $resource;
    }

    abstract public function load(): void;
}
