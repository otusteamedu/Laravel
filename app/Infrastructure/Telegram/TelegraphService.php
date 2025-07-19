<?php

namespace App\Infrastructure\Telegram;

use Illuminate\Support\Stringable;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class TelegraphService extends WebhookHandler
{
    public function start(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('Подписаться')->requestContact(),
            ])
            ->resize();

        $this->chat->message('Выбери действие в меню')
            ->replyKeyboard($keyboard)
            ->send();
    }

    public function about(): void
    {
        $this->reply("Привет! Я бот ToDo");
    }

    public function info(): void
    {
        $senderId = $this->message->from()->id();

        $this->reply("*Твой Telegram ID:*\n\n" . $senderId);
    }

    public function subscribe() {}

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->reply('Выбери нужный пункт в меню');
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $senderId = $this->message->from()->id();

        if (!empty($this->message->contact())) {
            $contactUserId = $this->message->contact()->userId();
            //$this->reply($this->message->contact()->phoneNumber());

            if ($contactUserId == $senderId) {
                $this->reply('Это твой контакт');
            } else {
                $this->reply('Это *НЕ ТВОЙ* контакт');
            }
        } else {
            $this->reply("Пока я умею принимать только контакты");
        }

        $this->chat->deleteMessage($this->message->id())->send();
    }
}
