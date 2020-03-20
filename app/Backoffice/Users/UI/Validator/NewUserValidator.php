<?php

namespace App\Backoffice\Users\UI\Validator;

use App\Backoffice\Users\Domain\UserEmail;
use App\Backoffice\Users\Domain\UserPassword;
use Cordo\Core\UI\Validator\AbstractValidator;

class NewUserValidator extends AbstractValidator
{
    protected function validationRules(): void
    {
        $this->validator
            ->required('password')
            ->string()
            ->lengthBetween(UserPassword::PASSWORD_MIN_LENGTH, UserPassword::PASSWORD_MAX_LENGTH);

        $this->validator
            ->required('email')
            ->email()
            ->lengthBetween(0, UserEmail::EMAIL_MAX_LENGTH);
    }
}
