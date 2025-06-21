<?php

namespace App\Console\Commands;

use App\Services\News\Handlers\GetLatestHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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

        $latestNews = $getLatestNewsUseCase()->results;

        Cache::put('latest_news_list', $latestNews, env('LATEST_NEWS_CACHE_TIME', 600));

        $this->info('Кэш новостей успешно прогрет.');
    }
}
