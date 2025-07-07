<?php

namespace App\Logging;

use Monolog\Handler\FilterHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FallbackGroupHandler;
use Monolog\Formatter\LineFormatter;

class TelegramLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('telegram');

        // Настройки для разных уровней логирования
        $levels = [
            'error' => [
                'chat_id' => env('TELEGRAM_ERROR_CHAT_ID'),
                'emoji' => '⚠️ ERROR',
                'level' => Level::Error,
            ],
            'critical' => [
                'chat_id' => env('TELEGRAM_CRITICAL_CHAT_ID'),
                'emoji' => '🔥 CRITICAL',
                'level' => Level::Critical,
            ],
            'alert' => [
                'chat_id' => env('TELEGRAM_ALERT_CHAT_ID'),
                'emoji' => '🚨 ALERT',
                'level' => Level::Alert,
            ],
            'emergency' => [
                'chat_id' => env('TELEGRAM_EMERGENCY_CHAT_ID'),
                'emoji' => '💥 EMERGENCY',
                'level' => Level::Emergency,
            ],
        ];

        foreach ($levels as $levelName => $settings) {
            // Telegram Handler (основной)
            $telegramHandler = new TelegramBotHandler(
                env('TELEGRAM_BOT_TOKEN'),
                $settings['chat_id'],
                $settings['level']
            );
            $telegramHandler->setFormatter(
                new LineFormatter("{$settings['emoji']}: %message% %context% %extra%\n")
            );

            // File Handler (резервный)
            $fileHandler = new StreamHandler(
                storage_path("logs/telegram_{$levelName}.log"),
                $settings['level']
            );
            $fileHandler->setFormatter(
                new LineFormatter("[%datetime%] %level_name%: %message% %context% %extra%\n")
            );

            // Группа с fallback: сначала Telegram, потом файл
            $fallbackGroup = new FallbackGroupHandler([$telegramHandler, $fileHandler]);

            $filterHandler = new FilterHandler(
                $fallbackGroup,
                $settings['level'],
                $settings['level']
            );

            $logger->pushHandler($filterHandler);


        }

        return $logger;
    }

}
