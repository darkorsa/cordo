<?php

namespace System\UI\Validator;

interface ValidatorInterface
{
    public function isValid(): bool;

    public function messages(): array;
}
