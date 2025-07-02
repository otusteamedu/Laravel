<?php

namespace App\Services\Commands\DeleteNews;

use App\Infrastructure\Cache\CacheInterface;
use App\Services\Exceptions\News\NewsNotFoundException;
use App\Services\Exceptions\News\NewsSaveException;
use App\Services\Repositories\NewsRepositoryInterface;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private CacheInterface $cache
    ) {
    }

    public function handle(Command $command): bool
    {
        $news = $this->newsRepository->find($command->id);

        if (!$news) {
            throw new NewsNotFoundException('Новость не найдена');
        }

        $result = $this->newsRepository->delete($news);

        if (!$result) {
            throw new NewsSaveException("Не удалось удалить новость '{$command-> id}'");
        }

        $this->cache->flushTagged('news');
        $this->cache->flushTagged('news_count');

        return $result;
    }
}
