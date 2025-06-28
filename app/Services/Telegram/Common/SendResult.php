<?php

namespace App\Services\Telegram\Common;

use App\Services\Telegram\Common\Recipient;

/**
 * Результат отправи сообщаения
 */
final readonly class SendResult
{
    /**
     * @param Recipient $recipient
     * @param bool $result
     * @param string|null $error
     */
    public function __construct(
        public Recipient $recipient,
        public bool $result,
        public ?string $error = null,
    ) {}
}
