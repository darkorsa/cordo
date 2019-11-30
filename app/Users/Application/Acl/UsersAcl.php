<?php

declare(strict_types=1);

namespace App\Users\Application\Acl;

use App\Auth\SharedKernel\Enum\SystemRole;
use System\Application\Service\Register\AclRegister;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class UsersAcl extends AclRegister
{
    public function register(): void
    {
        $this->acl->addResource(new Resource($this->resource))
            ->allow(SystemRole::GUEST(), $this->resource)
            ->allow(SystemRole::LOGGED(), $this->resource);
    }
}