<?php
namespace Src\OrdersEventSource\Domain\ValueObjects;

class OrderPrice
{
    public function __construct(
        private int $value,
        private string $symbol
    ) {}

    public function value(): string
    {
        return $this->value;
    }

    public function add(self $other): self
    {
        if ($this->symbol !== $other->symbol) {
            throw new \DomainException('Currency mismatch');
        }

        return new self(
            $this->value + $other->value,
            $this->symbol
        );
    }
}