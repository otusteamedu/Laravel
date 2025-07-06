<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification\Telegram;

interface TelegramServiceInterface
{
    public function sendMessage(string $message): bool;
}
