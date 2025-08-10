<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier\Filters;

use App\Infrastructure\Notifier\FilteredNotifier\FilterInterface;

class BannedFilter implements FilterInterface
{

    public const BANNED = [3, 2];

    public function filter(string $text, int $recipientId): bool
    {
        if (in_array($recipientId, self::BANNED)) {
            return false;
        }

        return true;
    }
}
