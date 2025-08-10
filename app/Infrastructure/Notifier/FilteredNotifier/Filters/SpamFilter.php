<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier\Filters;

use App\Infrastructure\Notifier\FilteredNotifier\FilterInterface;

class SpamFilter implements FilterInterface
{
    public function filter(string $text, int $recipientId): bool
    {
        $badWords = ['spam', 'bad', 'link', 'buy', 'check out'];

        foreach ($badWords as $badWord) {
            if (str_contains($text, $badWord)) {
                return false;
            }
        }

        return true;
    }
}
