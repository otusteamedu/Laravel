<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | Your Telegram Bot Token received from @BotFather
    |
    */
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Telegram Chat ID
    |--------------------------------------------------------------------------
    |
    | Chat ID where logs will be sent
    |
    */
    'chat_id' => env('TELEGRAM_CHAT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Log Level
    |--------------------------------------------------------------------------
    |
    | Minimum log level for Telegram notifications
    | Available levels: debug, info, notice, warning, error, critical, alert, emergency
    |
    */
    'log_level' => env('TELEGRAM_LOG_LEVEL', 'error'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | HTTP request timeout in seconds
    |
    */
    'timeout' => env('TELEGRAM_LOG_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Fallback Channel
    |--------------------------------------------------------------------------
    |
    | Laravel log channel to use when Telegram API is unavailable
    |
    */
    'fallback_channel' => env('TELEGRAM_LOG_FALLBACK', 'single'),

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Telegram Logging
    |--------------------------------------------------------------------------
    |
    | Enable or disable Telegram logging
    |
    */
    'enabled' => env('TELEGRAM_LOG_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Message Format
    |--------------------------------------------------------------------------
    |
    | Customize message format with placeholders:
    | {level}, {message}, {context}, {extra}, {datetime}
    |
    */
    'message_format' => '<b>[{level}]</b> {message}',

    /*
    |--------------------------------------------------------------------------
    | Include Context
    |--------------------------------------------------------------------------
    |
    | Include context data in messages
    |
    */
    'include_context' => env('TELEGRAM_LOG_INCLUDE_CONTEXT', true),

    /*
    |--------------------------------------------------------------------------
    | Include Extra
    |--------------------------------------------------------------------------
    |
    | Include extra data in messages
    |
    */
    'include_extra' => env('TELEGRAM_LOG_INCLUDE_EXTRA', true),

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | Date format for timestamp
    |
    */
    'date_format' => env('TELEGRAM_LOG_DATE_FORMAT', 'Y-m-d H:i:s'),
]; 