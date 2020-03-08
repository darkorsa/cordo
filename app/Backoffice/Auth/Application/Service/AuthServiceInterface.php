<?php

namespace App\Backoffice\Auth\Application\Service;

interface AuthServiceInterface
{
    public function hashPassword(string $password): string;
}
