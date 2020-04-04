<?php

namespace App\Backoffice\Acl\Application\Command;

class DeleteUserAcl
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
