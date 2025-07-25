<?php

namespace App\Ddd\Domain\ValueObjects;

enum Status: string
{
    case Pending = 'pending';
    case Succeeded = 'succeeded';
    case Canceled = 'canceled';

    public function message(): string
    {
        return match ($this) {
            self::Pending => 'Ожидает подтверждения',
            self::Succeeded => 'Выполнен',
            self::Canceled => 'Не прошел',
        };
    }
}