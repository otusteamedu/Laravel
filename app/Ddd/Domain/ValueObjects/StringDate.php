<?php

namespace App\Ddd\Domain\ValueObjects;

use InvalidArgumentException;

class StringDate
{
    private ?string $value;
    
    public function __construct(?string $value = null) {
        $this->assertDateIsValid($value);
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function assertDateIsValid(?string $value)
    {
        if (!empty($value) && !strtotime($value)) {
            throw new InvalidArgumentException('Некорректная дата');
        }
    }
}