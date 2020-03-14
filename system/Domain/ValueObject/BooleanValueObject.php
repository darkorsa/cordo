<?php

namespace System\Domain\ValueObject;

abstract class BooleanValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) (int) $this->value();
    }
}
