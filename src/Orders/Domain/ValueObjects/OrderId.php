<?php
namespace Src\Orders\Domain\ValueObjects;

class OrderId
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }
}