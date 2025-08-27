<?php

namespace App\Domain\Cart\ValueObjects;

class CartToken
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Cart token cannot be empty');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(CartToken $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new self(bin2hex(random_bytes(16)));
    }
}
