<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Common\SendReport;
use Illuminate\Support\Facades\Http;
use App\Services\Telegram\Common\Send;
use Illuminate\Support\Facades\Config;
use App\Services\Telegram\Common\SendResult;
use App\Services\Telegram\Exceptions\SendTelergamMessageException;

class TelegramService
{
    /**
     * Метод отправки сообщения получателям.
     *
     * @return SendReport
     */
    public function send(Send $send): SendReport
    {
        $apiKey = Config::get('telegram.api_key', '');

        $url = "https://api.telegram.org/bot" . $apiKey . "/SendMessage";

        $report = [];

        foreach ($send->recipients as $recipient) {
            try {
                $response = Http::post($url, [
                    'chat_id'    => $recipient->recipient,
                    'parse_mode' => $send->parseMode->value,
                    'text'       => $send->message
                ]);

                if ($response->getStatusCode() >= 400) {
                    $report[] = new SendResult(
                        recipient: $recipient,
                        result: false,
                        error: $response->getBody()
                    );
                }

                if ($response->getStatusCode() === 200) {
                    $report[] = new SendResult(
                        recipient: $recipient,
                        result: true,
                    );
                }
            } catch (\Throwable $exception) {
                throw new SendTelergamMessageException($exception->getMessage());
            }
        }

        return new SendReport($report);
    }
}
