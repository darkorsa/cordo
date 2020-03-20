<?php

namespace App\Backoffice\Users\UI\Validator;

use App\Backoffice\Users\Application\Service\UserQueryService;
use Particle\Validator\Exception\InvalidValueException;
use Cordo\Core\Application\Exception\ResourceNotFoundException;

class EmailExistsValidation
{
    private UserQueryService $service;

    public function __construct(UserQueryService $service)
    {
        $this->service = $service;
    }

    public function __invoke($value)
    {
        try {
            $this->service->getOneByEmail($value);
            throw new InvalidValueException('Email address is not unique', 'Unique::EMAIL_NOT_UNIQUE');
        } catch (ResourceNotFoundException $exception) {
            return true;
        }
    }
}
