<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Cordo\Core\Application\Query\QueryFilterInterface;

interface AclQuery
{
    public function count(?QueryFilterInterface $filter = null): int;

    public function getById(string $id, ?QueryFilterInterface $filter = null): AclView;

    public function getByUserId(string $userId, ?QueryFilterInterface $aclFilter = null): AclView;

    public function getAll(?QueryFilterInterface $filter = null): ArrayCollection;
}
