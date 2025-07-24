<?php

namespace App\Ddd\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class Id
{
    private int $value;
    
    public function __construct(int $value) {
        $this->assertIdIsValid($value);
        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function assertIdIsValid(int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Идентификатор должен быть натуральным числом');
        }
    }
}