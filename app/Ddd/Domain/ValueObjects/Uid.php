<?php

namespace App\Ddd\Domain\ValueObjects;

use InvalidArgumentException;

class Uid
{
    private string $value;
    
    public function __construct(string $value) {
        $this->assertUidIsValid($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function assertUidIsValid(string $value)
    {
        if (!preg_match('/^[a-z0-9-]{36}$/', $value)) {
            throw new InvalidArgumentException('Некорректный uid');
        }
    }
}