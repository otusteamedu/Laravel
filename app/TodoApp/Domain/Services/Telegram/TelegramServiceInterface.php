<?php

namespace App\TodoApp\Domain\Services\Telegram;


interface TelegramServiceInterface
{
    /**
     * Метод отправки сообщения получателям.
     *
     * @return SendResult
     * @throws SendTelergamMessageException
     */
    public function send(Send $send): SendResult;
}
