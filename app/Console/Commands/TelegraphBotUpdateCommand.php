<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DefStudio\Telegraph\Models\TelegraphBot;

class TelegraphBotUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegraph_bot:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление webhook и меню бота';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var TelegraphBot $bot */
        $bot = TelegraphBot::find(1);

        $bot->registerCommands([
            '/start' => 'Начать общение',
            '/about' => 'О боте',
            '/subscribe' => 'Подписаться на оповещения с сайта',
            '/unsubscribe' => 'Отписаться от оповещений с сайта',
        ])->send();

        $bot->registerWebhook()->send();

        return self::SUCCESS;
    }
}
