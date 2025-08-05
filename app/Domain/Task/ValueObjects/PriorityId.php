<?php

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class PriorityId
{
    public function __construct(
        private int $value
    ) {
        if ($value <= 0) {
            throw new InvalidArgumentException('Priority ID должен быть положительным числом');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(PriorityId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }
}