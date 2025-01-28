<?php

use Cordo\Core\UI\Validator\AbstractValidator;

require_once __DIR__ . '/../../bootstrap/app.php';

function createValidator(array $data)
{
    return new class($data) extends AbstractValidator
    {
        protected function rules(): array
        {
            return [
                'email' => 'required|email|max:25'
            ];
        }
    };
}

test('validation successfull', function () {
    $validator = createValidator([
        'email' => 'test@example.com',
    ]);

    expect($validator->passes())->toBeTrue();
    expect($validator->messages()->toArray())->toBe([]);
});

test('validation failed', function () {
    $validator = createValidator([
        'email' => '4werfwe',
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->messages()->toArray())->toBe([
        'email' => [
            'The email must be a valid email address.'
        ],
    ]);
});
