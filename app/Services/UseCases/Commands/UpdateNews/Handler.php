<?php

namespace App\Services\UseCases\Commands\UpdateNews;

use App\Infrastructure\Cache\CacheInterface;
use App\Services\DTO\News\NewsDTO;
use App\Services\Exceptions\News\NewsNotFoundException;
use App\Services\Repositories\NewsRepositoryInterface;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private CacheInterface $cache,
    ) {
    }

    /**
     * @param Command $command
     * @param bool    $isAdmin
     *
     * @return NewsDTO
     * @throws NewsNotFoundException
     */
    public function handle(Command $command, bool $isAdmin = false): NewsDTO
    {
        $news = $this->newsRepository->find($command->id);

        if (!$news) {
            throw new NewsNotFoundException('Новость не найдена');
        }

        $isChangedActivityCount = $news->is_draft !== $command->isDraft;

        $news->title = $command->title;
        $news->content = $command->content;
        $news->published_at = $command->publishedAt;
        $news->is_draft = $command->isDraft;
        $news->attachCategory($command->categoryId);

        if ($isAdmin) {
            $news->attachUser($command->userId);
        }

        $this->newsRepository->save($news);

        $this->cache->flushTagged('news');

        if ($isChangedActivityCount) {
            $this->cache->flushTagged('news_count');
        }

        return new NewsDTO(
            id: $news->id,
            title: $news->title,
            content: $news->content,
            isDraft: $news->is_draft,
            thumbnail: $news->is_thumbnail,
            createdAt: $news->created_at,
            updatedAt: $news->updated_at,
            publishedAt: $news->published_at,
        );
    }
}
