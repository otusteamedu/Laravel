<?php

namespace App\Domain\Task\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;

final readonly class TaskDueDate
{
    private function __construct(
        private Carbon $value
    ) {
    }
    
    public static function create(Carbon $date): self
    {
        if ($date->isPast()) {
            throw new InvalidArgumentException('Дата выполнения задачи не может быть в прошлом');
        }
        
        return new self($date);
    }
    
    public static function fromPersistence(Carbon $date): self
    {
        return new self($date);
    }

    public function value(): Carbon
    {
        return $this->value;
    }

    public function equals(TaskDueDate $other): bool
    {
        return $this->value->equalTo($other->value);
    }

    public function __toString(): string
    {
        return $this->value->toDateTimeString();
    }

    public function isOverdue(): bool
    {
        return $this->value->isPast();
    }

    public function daysUntilDue(): int
    {
        return now()->diffInDays($this->value, false);
    }

    public static function fromCarbon(Carbon $date): self
    {
        return self::create($date);
    }

    public static function fromString(string $date): self
    {
        return self::create(Carbon::parse($date));
    }
}