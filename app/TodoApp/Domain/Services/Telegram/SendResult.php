<?php

namespace App\TodoApp\Domain\Services\Telegram;


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
