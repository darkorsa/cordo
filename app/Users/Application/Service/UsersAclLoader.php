<?php

declare(strict_types=1);

namespace App\Users\Application\Service;

use App\Auth\SharedKernel\Enum\SystemRole;
use System\Application\Service\Loader\BaseAclLoader;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class UsersAclLoader extends BaseAclLoader
{
    public function load(): void
    {
        $this->acl->addResource(new Resource($this->resource))
            ->allow(SystemRole::GUEST(), $this->resource)
            ->allow(SystemRole::LOGGED(), $this->resource);
    }
}
