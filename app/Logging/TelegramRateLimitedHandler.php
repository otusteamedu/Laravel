<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Support\Facades\RateLimiter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Handler\TelegramBotHandler;
use Illuminate\Support\Facades\Log;

class TelegramRateLimitedHandler extends AbstractProcessingHandler
{
    public function __construct(
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
        protected string $apiKey,
        protected string $channel,
        protected string $fallbackChannel)
    {
        parent::__construct($level, $bubble);
    }


    /**
     * @param LogRecord $record
     *
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $executed = RateLimiter::attempt(
            'telegram-log-send:' . $this->channel,
            20,
            function () use ($record) {
                $handler = new TelegramBotHandler(
                    $this->apiKey,
                    $this->channel,
                    $record->level->value,
                );
                $handler->setFormatter(new TelegramFormatter());
                $handler->handle($record);
            },
            60
        );

        if (!$executed) {
            Log::channel($this->fallbackChannel)->error($record->message);
        }
    }
}
