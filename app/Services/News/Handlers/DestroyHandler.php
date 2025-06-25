<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Exceptions\NewsNotFoundException;
use App\Services\News\Repositories\NewsRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class DestroyHandler
{
    public function __construct(private NewsRepositoryInterface $newsRepository)
    {
    }

    /**
     * @param int $newsId
     *
     * @return bool|null
     * @throws NewsNotFoundException
     */
    public function __invoke(int $newsId): ?bool {
        $news = $this->newsRepository->find($newsId);

        if (!$news) {
            throw new NewsNotFoundException('News not found');
        }

        $res = $this->newsRepository->delete($news);

        Cache::tags('news')->flush(); // Очистить все кэши с тегом 'news'
        Cache::tags('news_count')->flush();

        return $res;
    }
}
