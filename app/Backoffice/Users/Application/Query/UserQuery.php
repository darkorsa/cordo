<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Cordo\Core\Application\Query\QueryFilterInterface;

interface UserQuery
{
    public function count(?QueryFilterInterface $queryFilter = null): int;

    public function getOne(QueryFilterInterface $queryFilter): UserView;

    public function getAll(?QueryFilterInterface $queryFilter = null): ArrayCollection;
}
