<?php

namespace App\Domain\Services\Telegram\DTO;


/**
 * Результат отправи сообщаения
 */
final readonly class SendResult
{
    /**
     * @param bool $result
     * @param string|null $error
     */
    public function __construct(
        public bool $result,
        public ?string $error = null,
    ) {}
}
