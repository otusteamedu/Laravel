<?php

namespace App\Console\Commands;

use App\Services\News\Handlers\GetLatestHandler;
use Illuminate\Console\Command;

class WarmCacheNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm cache for news';

    /**
     * Execute the console command.
     */
    public function handle(GetLatestHandler $getLatestNewsUseCase): void
    {
        $this->info('Начинаем прогрев кэша новостей...');
        $getLatestNewsUseCase();
        $this->info('Кэш новостей успешно прогрет.');
    }
}
