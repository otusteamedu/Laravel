<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Support\Facades\RateLimiter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Handler\TelegramBotHandler;
use Illuminate\Support\Facades\Log;

class TelegramRateLimitedHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        $executed = RateLimiter::attempt(
            'telegram-log-send:' . env('LOG_TELEGRAM_CHAT_ID'),
            1,
            function () use ($record) {
                $handler = new TelegramBotHandler(
                    env('LOG_TELEGRAM_BOT_TOKEN'),
                    env('LOG_TELEGRAM_CHAT_ID'),
                    $record->level->value,
                );
                $handler->setFormatter(new TelegramFormatter());
                $handler->handle($record);
            },
            60
        );

        if (!$executed) {
            Log::channel('single')->error($record->message);
           // Log::channel('telegram_fallback')->error($record->message);
        }
    }
}
