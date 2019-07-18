<?php declare(strict_types=1);

namespace App\Auth\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;

interface AclQuery
{
    public function count(?AclFilter $filter = null): int;

    public function getById(string $id, ?AclFilter $filter = null): AclView;

    public function getByUserId(string $userId, ?AclFilter $aclFilter = null): AclView;

    public function getAll(?AclFilter $filter = null): ArrayCollection;
}
