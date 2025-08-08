<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\TelegramBotHandler;

class TelegramLogger
{
    public function __invoke(array $config)
    {
        return new Logger('telegram', [
            new TelegramBotHandler(
                $config['token'],
                $config['chat_id'],
                Logger::INFO, 
                true, 
                null, 
                true
            )
        ]);
    }
}
