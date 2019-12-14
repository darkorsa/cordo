<?php declare(strict_types=1);

namespace System\Module\Auth\Application\Query;

class AclFilter
{
    private $userId;

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): AclFilter
    {
        $this->userId = $userId;

        return $this;
    }
}
