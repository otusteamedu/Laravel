<?php

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class TaskDescription
{
    public function __construct(
        string $value
    ) {
        $trimmed = trim($value);
        
        if (empty($trimmed)) {
            throw new InvalidArgumentException('Описание задачи не может быть пустым');
        }
        
        if (mb_strlen($trimmed) > 65535) {
            throw new InvalidArgumentException('Описание задачи слишком длинное');
        }
        
        $this->value = $trimmed;
    }
    
    private readonly string $value;

    public function value(): string
    {
        return $this->value;
    }

    public function equals(TaskDescription $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}