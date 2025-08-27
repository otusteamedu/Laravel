<?php

namespace App\Domain\Cart\ValueObjects;

class CartItemQuantity
{
    private int $value;
    private const MIN_QUANTITY = 1;
    private const MAX_QUANTITY = 100;

    public function __construct(int $value)
    {
        if ($value < self::MIN_QUANTITY) {
            throw new \InvalidArgumentException(
                sprintf('Quantity cannot be less than %d', self::MIN_QUANTITY)
            );
        }

        if ($value > self::MAX_QUANTITY) {
            throw new \InvalidArgumentException(
                sprintf('Quantity cannot exceed %d', self::MAX_QUANTITY)
            );
        }

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(CartItemQuantity $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function add(CartItemQuantity $other): CartItemQuantity
    {
        $newValue = $this->value + $other->getValue();
        return new self($newValue);
    }

    public function subtract(CartItemQuantity $other): CartItemQuantity
    {
        $newValue = $this->value - $other->getValue();
        return new self($newValue);
    }

    public function multiply(int $multiplier): CartItemQuantity
    {
        $newValue = $this->value * $multiplier;
        return new self($newValue);
    }

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function isMax(): bool
    {
        return $this->value === self::MAX_QUANTITY;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function createDefault(): self
    {
        return new self(self::MIN_QUANTITY);
    }

    public static function createFromInt(int $value): self
    {
        return new self($value);
    }
}
