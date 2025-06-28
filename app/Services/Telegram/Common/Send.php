<?php

namespace App\Services\Telegram\Common;

use App\Services\Telegram\Common\ParseModeEnum;


final readonly class Send
{
    /**
     * @param array $recipients
     * @param string $message
     * @param ?ParseModeEnum $parseMode
     */
    public function __construct(
        public array $recipients,
        public string $message,
        public ?ParseModeEnum $parseMode = ParseModeEnum::HTML
    ) {}
}
