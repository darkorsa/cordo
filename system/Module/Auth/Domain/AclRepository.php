<?php

declare(strict_types=1);

namespace System\Module\Auth\Domain;

interface AclRepository
{
    public function add(Acl $acl): void;

    public function update(Acl $acl): void;

    public function delete(Acl $acl): void;
}
