<?php

namespace App\Auth\Application\Service;

interface AuthServiceInterface
{
    public function hashPassword(string $password): string;
}
