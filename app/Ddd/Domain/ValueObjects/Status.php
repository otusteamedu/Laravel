<?php

namespace App\Ddd\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class Status
{
    const POSSIBLE_STATUSES = ['pending', 'succeeded', 'canceled'];

    private string $value;
    
    public function __construct(string $value) {
        $this->assertStatusIsValid($value);
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function assertStatusIsValid(string $value)
    {
        if (!in_array($value, self::POSSIBLE_STATUSES)) {
            throw new InvalidArgumentException('Некорректный uid');
        }
    }
}