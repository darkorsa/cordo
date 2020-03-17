<?php

declare(strict_types=1);

namespace System\UI\Validator;

use Exception;
use Particle\Validator\Validator;
use Particle\Validator\ValidationResult;

abstract class AbstractValidator implements ValidatorInterface
{
    protected Validator $validator;

    protected array $data;

    protected ?ValidationResult $result = null;

    public function __construct(array $data)
    {
        $this->validator = new Validator();
        $this->data = $data;
    }

    public function addCallbackValidator(string $field, callable $callback)
    {
        $this->validator->required($field)->callback($callback);
    }

    public function isValid(): bool
    {
        $this->validationRules();
        $this->result = $this->validator->validate($this->data);

        return $this->result->isValid();
    }

    public function messages(): array
    {
        if (!$this->result) {
            throw new Exception('No validation messages. Execute validate method first!');
        }

        return $this->result->getMessages();
    }

    abstract protected function validationRules(): void;
}
