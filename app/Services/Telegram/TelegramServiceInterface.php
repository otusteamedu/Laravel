<?php

declare(strict_types=1);

namespace App\Services\Telegram;

interface TelegramServiceInterface
{
    public function sendMessage(string $message): bool;
}
