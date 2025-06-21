<?php

declare(strict_types=1);

namespace App\Services\News\Handlers;
use App\Services\News\Repositories\NewsRepositoryInterface;
use App\Services\News\Results\Fetcher;
use Illuminate\Support\Facades\Cache;

class GetLatestHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository, private Fetcher $fetcher)
    {
    }

    public function __invoke()
    {
        // without cache
        //Cache::forget('latest_news_list');

        //$start = microtime(true);

        // without cache
        //$newsCollection = $this->newsRepository->getLatest();
        //$lastestNews =  $this->fetcher->fetch($newsCollection);

        //$lastestNews = Cache::remember('latest_news_list', env('LATEST_NEWS_CACHE_TIME', 600), function () {

        return Cache::remember('latest_news_list', env('LATEST_NEWS_CACHE_TIME', 600), function () {
            $newsCollection = $this->newsRepository->getLatest();

            return $this->fetcher->fetch($newsCollection);
        });

        //$end = microtime(true);
        //$duration = $end - $start;

        //logger()->info("Время выполнения с кэшем: {$duration} секунд");

        // without cache
        //logger()->info("Время выполнения без кэша: {$duration} секунд");

        //return $lastestNews;


        /*
         [2025-06-21 11:09:18] local.INFO: Время выполнения без кэша: 0.0029261112213135 секунд
         [2025-06-21 11:10:10] local.INFO: Время выполнения с кэшем: 0.0009160041809082 секунд
         */
    }
}
