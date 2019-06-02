<?php

namespace App\Users\UI\Validator;

use App\Users\Domain\User;
use Particle\Validator\Validator;
use Particle\Validator\ValidationResult;
use App\Users\Application\Service\UserService;
use Particle\Validator\Exception\InvalidValueException;
use System\Application\Exception\ResourceNotFoundException;

trait UserValidator
{
    private function validate(array $params, bool $requirePassword = false): ValidationResult
    {
        $service = $this->container->get(UserService::class);

        $validator = new Validator;
        if ($requirePassword) {
            $validator->required('password')->lengthBetween(User::PASSWORD_MIN_LENGTH, User::PASSWORD_MAX_LENGTH);
        }
        $validator->required('email')
            ->lessThan(User::EMAIL_MAX_LENGTH)
            ->email()
            ->callback(function ($value) use ($service) {
                try {
                    $service->getOneByEmail($value);
                    throw new InvalidValueException('Email address us not unique', 'Unique::EMAIL_NOT_UNIQUE');
                } catch (ResourceNotFoundException $ex) {
                    return true;
                }
            });

        return $validator->validate($params);
    }
}
