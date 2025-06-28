<?php

namespace App\Services\Telegram\Common;

/**
 * Получатель сообщения в телеграм
 */
final readonly class Recipient
{
    /**
     * @param int $recipient id получателя
     */
    public function __construct(
        public int $recipient
    ) {}
}
