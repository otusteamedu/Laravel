<?php

namespace App\Services\UseCases\Commands\CreateNews;

use App\Infrastructure\Cache\CacheInterface;
use App\Models\News;
use App\Services\DTO\News\NewsDTO;
use App\Services\Exceptions\News\NewsSaveException;
use App\Services\Repositories\NewsRepositoryInterface;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private CacheInterface $cache
    ) {
    }

    public function handle(Command $command): NewsDTO
    {
        $news = new News();

        $news->title = $command->title;
        $news->content = $command->content;
        $news->published_at = $command->publishedAt;
        $news->is_draft = $command->isDraft;

        $news->attachCategory($command->categoryId);
        $news->attachUser($command->userId);

        $result = $this->newsRepository->save($news);

        if (!$result) {
            throw new NewsSaveException("Не удалось сохранить новость '{$command->title}'");
        }

        $this->cache->flushTagged('news');
        $this->cache->flushTagged('news_count');

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
