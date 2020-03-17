<?php

namespace System\UI\Http\Validator;

interface ValidatorInterface
{
    public function isValid(): bool;

    public function messages(): array;
}
