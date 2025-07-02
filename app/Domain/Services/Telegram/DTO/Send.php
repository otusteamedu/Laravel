<?php

namespace App\Domain\Services\Telegram\DTO;

use App\Domain\Services\Telegram\ValueObject\ParseModeEnum;


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
