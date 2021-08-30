<?php

use Cordo\Core\UI\Validator\AbstractValidator;

function createValidator(array $data, array $customDefaultMessages = null)
{
    return new class($data, $customDefaultMessages) extends AbstractValidator
    {
        protected function validationRules(): void
        {
            $this->validator
                ->required('email')
                ->email()
                ->length(15);
        }
    };
}

test('validation with custom messages', function () {
    $data = [
        'email' => 'test@example',
    ];
    $customMessages = require resources_path('lang/pl/validation.php');
    $validator = createValidator($data, $customMessages);

    expect($validator->isValid($data, $customMessages))->toBeFalse();
    expect($validator->messages())->toEqual([
        'email' => [
            'Email::INVALID_VALUE' => 'Niepoprawny adres email',
            'Length::TOO_SHORT' => 'Ilość znaków jest zbyt mała i powinna mieć wartość 15',
        ],
    ]);
});
