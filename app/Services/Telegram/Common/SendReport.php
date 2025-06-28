<?php

namespace App\Services\Telegram\Common;

use App\Services\Telegram\Common\SendResult;

/**
 * Резултат отправки сообщения получателям
 */
final readonly class SendReport
{
    /**
     * @param SendResult[] $results
     */
    public function __construct(
        public array $results
    ) {}
}
