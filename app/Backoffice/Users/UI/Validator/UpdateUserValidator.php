<?php

namespace App\Backoffice\Users\UI\Validator;

use Particle\Validator\Rule\InArray;
use App\Backoffice\Users\Domain\UserEmail;
use Cordo\Core\UI\Validator\AbstractValidator;

class UpdateUserValidator extends AbstractValidator
{
    protected function validationRules(): void
    {
        $this->validator
            ->required('email')
            ->email()
            ->lengthBetween(0, UserEmail::EMAIL_MAX_LENGTH);

        $this->validator
            ->required('active')
            ->integer()
            ->inArray([0, 1], InArray::NOT_STRICT);
    }
}
