<?php

namespace App\Infrastructure\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\TodoApp\Domain\Services\Telegram\Send;
use App\TodoApp\Domain\Services\Telegram\SendResult;
use App\TodoApp\Domain\Services\Telegram\TelegramServiceInterface;
use App\TodoApp\Domain\Services\Telegram\SendTelergamMessageException;



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
