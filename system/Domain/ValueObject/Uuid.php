<?php

declare(strict_types=1);

namespace Cordo\Core\Domain\ValueObject;

use Assert\Assert;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    protected string $value;

    public function __construct(string $value)
    {
        Assert::that($value)
            ->notEmpty()
            ->uuid();

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public static function random(): self
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
