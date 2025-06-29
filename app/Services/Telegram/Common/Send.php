<?php

namespace App\Services\Telegram\Common;

use App\Services\Telegram\Common\ParseModeEnum;


final readonly class Send
{
    /**
     * @param int $recipient
     * @param string $message
     * @param ?ParseModeEnum $parseMode
     */
    public function __construct(
        public int $recipient,
        public string $message,
        public ?ParseModeEnum $parseMode = ParseModeEnum::HTML
    ) {}
}
