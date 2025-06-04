<?php
declare(strict_types=1);

namespace App\Services\News\Handlers;

use App\Services\News\Exceptions\NewsNotFoundException;
use App\Services\News\Repositories\NewsRepositoryInterface;

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

        return $this->newsRepository->delete($news);
    }
}
