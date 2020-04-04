<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Domain;

interface AclRepository
{
    public function find(string $id): Acl;

    public function add(Acl $acl): void;

    public function update(Acl $acl): void;

    public function delete(Acl $acl): void;
}
