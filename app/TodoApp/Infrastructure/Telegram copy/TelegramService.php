<?php

namespace App\Infrastructure\Telegram;

use App\Services\Telegram\TelegramServiceInterface;
use Illuminate\Support\Facades\Http;
use App\Services\Telegram\Common\Send;
use Illuminate\Support\Facades\Config;
use App\Services\Telegram\Common\SendResult;
use App\Services\Telegram\Exceptions\SendTelergamMessageException;


class TelegramService implements TelegramServiceInterface
{
    /**
     * Метод отправки сообщения получателям.
     *
     * @return SendResult
     */
    public function send(Send $send): SendResult
    {
        $apiKey = Config::get('telegram.api_key', '');

        $url = "https://api.telegram.org/bot" . $apiKey . "/SendMessage";

        try {
            $response = Http::post($url, [
                'chat_id'    => $send->recipient,
                'parse_mode' => $send->parseMode->value,
                'text'       => $send->message
            ]);

            if ($response->getStatusCode() >= 400) {
                $result = new SendResult(
                    result: false,
                    error: $response->getBody()
                );
            }

            if ($response->getStatusCode() === 200) {
                $result = new SendResult(
                    result: true,
                );
            }
        } catch (\Throwable $exception) {
            throw new SendTelergamMessageException($exception->getMessage());
        }

        return $result;
    }
}
