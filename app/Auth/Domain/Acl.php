<?php declare(strict_types=1);

namespace App\Auth\Domain;

use DateTime;
use Assert\Assert;
use App\Users\Domain\User;

class Acl
{
    private $id;

    private $id_user;

    private $privileges;

    private $createdAt;

    private $updatedAt;

    public function __construct(
        string $id,
        User $user,
        array $privileges,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        // id
        Assert::that($id)
            ->notEmpty()
            ->uuid();
        // email
        Assert::that($privileges)
            ->notEmpty();

        $this->id           = $id;
        $this->id_user      = $user;
        $this->privileges   = json_encode($privileges);
        $this->createdAt    = $createdAt;
        $this->updatedAt    = $updatedAt;
    }
}
