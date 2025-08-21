<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface TelegramServiceInterface
{
    public function sendMessage(string $message): bool;
}
