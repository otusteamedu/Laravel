<?php

namespace App\Domain\Apartment\ValueObjects;

class SerialNumber
{
    private int $number;

    public function __construct(int $number)
    {
        if ($number <= 0) {
            throw new \InvalidArgumentException('Serial number must be positive.');
        }
        $this->number = $number;
    }

    public function toInt(): int
    {
        return $this->number;
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }
}

