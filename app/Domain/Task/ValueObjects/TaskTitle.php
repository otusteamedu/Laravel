<?php

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class TaskTitle
{
    public function __construct(
        string $value
    ) {
        $trimmed = trim($value);
        
        if (empty($trimmed)) {
            throw new InvalidArgumentException('Название задачи не может быть пустым');
        }
        
        if (mb_strlen($trimmed) > 255) {
            throw new InvalidArgumentException('Название задачи не может быть длиннее 255 символов');
        }
        
        $this->value = $trimmed;
    }
    
    private readonly string $value;

    public function value(): string
    {
        return $this->value;
    }

    public function equals(TaskTitle $other): bool
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