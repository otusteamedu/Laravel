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
        return Cache::tags(['news'])->remember('latest_news_list', env('LATEST_NEWS_CACHE_TIME', 900), function () {
            $newsCollection = $this->newsRepository->getLatest();

            return $this->fetcher->fetch($newsCollection);
        });
    }
}
