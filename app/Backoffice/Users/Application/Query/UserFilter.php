<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Query;

class UserFilter
{
    private $offset;

    private $limit;

    private $isActive;

    public function setActive(bool $isActive): UserFilter
    {
        $this->isActive = (int) $isActive;

        return $this;
    }

    public function setOffset(int $offset): UserFilter
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit): UserFilter
    {
        $this->limit = $limit;

        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }
}
