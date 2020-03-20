<?php

namespace Cordo\Core\UI\Validator;

interface ValidatorInterface
{
    public function isValid(): bool;

    public function messages(): array;
}
