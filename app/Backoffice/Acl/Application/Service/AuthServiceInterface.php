<?php

namespace App\Backoffice\Acl\Application\Service;

interface AuthServiceInterface
{
    public function hashPassword(string $password): string;
}
