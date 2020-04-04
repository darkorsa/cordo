<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Application\Service;

use Laminas\Permissions\Acl\Acl;
use App\Backoffice\Acl\Application\Query\AclView;
use App\Backoffice\Acl\Application\Query\AclQuery;
use Cordo\Core\SharedKernel\Enum\SystemRole;
use App\Backoffice\Acl\Application\Query\AclFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Cordo\Core\Application\Exception\ResourceNotFoundException;

class AclQueryService
{
    private $aclQuery;

    public function __construct(AclQuery $aclQuery)
    {
        $this->aclQuery = $aclQuery;
    }

    public function getOneById(string $id, ?AclFilter $aclFilter = null): AclView
    {
        return $this->aclQuery->getById($id, $aclFilter);
    }

    public function getOneByUserId(string $userId, ?AclFilter $aclFilter = null): AclView
    {
        return $this->aclQuery->getByUserId($userId, $aclFilter);
    }

    public function getCollection(?AclFilter $aclFilter = null): ArrayCollection
    {
        return $this->aclQuery->getAll($aclFilter);
    }

    public function getCount(?AclFilter $aclFilter = null): int
    {
        return $this->aclQuery->count($aclFilter);
    }

    public function setUserAclPrivileges(SystemRole $role, string $userId, Acl $acl): void
    {
        try {
            $userPrivileges = $this->getOneByUserId($userId);
        } catch (ResourceNotFoundException $exteption) {
            return;
        }

        foreach ($userPrivileges->privileges() as $resource => $privileges) {
            $acl->allow((string) $role, $resource, $privileges);
        }
    }
}