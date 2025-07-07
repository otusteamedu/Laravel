<?php

namespace App\Ddd\Domain\ValueObjects;

use InvalidArgumentException;

class Amount
{
    private int $value;
    
    public function __construct(int $value) {
        $this->assertAmountIsValid($value);
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function assertAmountIsValid(int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Стоимость должна быть натуральным числом');
        }
    }
}