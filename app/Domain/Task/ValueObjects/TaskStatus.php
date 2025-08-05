<?php

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class TaskStatus
{
    public const NEW = 'новая';
    public const IN_PROGRESS = 'в работе';
    public const COMPLETED = 'выполнена';
    public const CANCELLED = 'отменена';

    private const VALID_STATUSES = [
        self::NEW,
        self::IN_PROGRESS,
        self::COMPLETED,
        self::CANCELLED,
    ];

    public function __construct(
        private string $value
    ) {
        if (!in_array($value, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Недопустимый статус задачи: %s. Допустимые статусы: %s',
                    $value,
                    implode(', ', self::VALID_STATUSES)
                )
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(TaskStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isNew(): bool
    {
        return $this->value === self::NEW;
    }

    public function isInProgress(): bool
    {
        return $this->value === self::IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->value === self::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->value === self::CANCELLED;
    }

    public function isActive(): bool
    {
        return !$this->isCompleted() && !$this->isCancelled();
    }

    public static function new(): self
    {
        return new self(self::NEW);
    }

    public static function inProgress(): self
    {
        return new self(self::IN_PROGRESS);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    public static function cancelled(): self
    {
        return new self(self::CANCELLED);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}