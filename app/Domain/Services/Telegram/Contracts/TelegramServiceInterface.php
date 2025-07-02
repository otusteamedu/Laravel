<?php

namespace App\Domain\Services\Telegram\Contracts;

use App\Domain\Services\Telegram\DTO\Send;
use App\Domain\Services\Telegram\DTO\SendResult;
use App\Domain\Services\Telegram\Exceptions\SendTelergamMessageException;


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
