<?php declare (strict_types=1);

namespace App\Users\Infrastructure\Doctrine\Query;

use System\Infractructure\Query\QueryFilter;

class UserFilter implements QueryFilter
{
    private $offset;

    private $limit;

    private $isActive;

    public function setActive(int $isActive): UserFilter
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function setOffset(int $offset) : UserFilter
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit) : UserFilter
    {
        $this->limit = $limit;

        return $this;
    }

    public function applyFilter($qb): void
    {
        if (!is_null($this->isActive)) {
            $qb->where('u.is_active', $this->isActive);
        }

        if (!is_null($this->offset) && !is_null($this->limit)) {
            $qb->setFirstResult($this->offset);
            $qb->setMaxResults($this->limit);
        }
    }
}
