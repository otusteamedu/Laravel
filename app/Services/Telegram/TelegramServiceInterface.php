<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Common\Send;
use App\Services\Telegram\Common\SendResult;
use App\Services\Telegram\Exceptions\SendTelergamMessageException;


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
