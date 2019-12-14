<?php

namespace System\Module\Auth\Application\Service;

interface AuthServiceInterface
{
    public function hashPassword(string $password): string;
}
