<?php

namespace App\Backoffice\Users\UI\Validator;

use Particle\Validator\Validator;
use Particle\Validator\ValidationResult;
use App\Backoffice\Users\Domain\UserEmail;
use App\Backoffice\Users\Domain\UserPassword;
use Particle\Validator\Exception\InvalidValueException;
use System\Application\Exception\ResourceNotFoundException;

trait UserValidator
{
    private function validate(
        array $params,
        bool $validatePassword,
        bool $updateMode
    ): ValidationResult {
        $service = $this->container->get('users.query.service');

        $validator = new Validator();
        if ($validatePassword) {
            $validator
                ->required('password')
                ->lengthBetween(UserPassword::PASSWORD_MIN_LENGTH, UserPassword::PASSWORD_MAX_LENGTH);
        }

        $validator->required('email')
            ->lessThan(UserEmail::EMAIL_MAX_LENGTH)
            ->email()
            ->callback(static function ($value) use ($service, $updateMode) {
                try {
                    if ($updateMode) {
                        return true;
                    }
                    $service->getOneByEmail($value);
                    throw new InvalidValueException('Email address us not unique', 'Unique::EMAIL_NOT_UNIQUE');
                } catch (ResourceNotFoundException $exception) {
                    return true;
                }
            });

        return $validator->validate($params);
    }

    private function validateNewUser(array $params): ValidationResult
    {
        return $this->validate($params, true, false);
    }

    private function validateUserUpdate(array $params): ValidationResult
    {
        return $this->validate($params, false, true);
    }
}
