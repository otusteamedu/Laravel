<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier;

interface FilterInterface
{
    public function filter(string $text, int $recipientId): bool;
}
