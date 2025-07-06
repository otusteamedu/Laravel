<?php

namespace App\Application\UseCases\News\Commands\UpdateNews;

use App\Domain\News\Exceptions\NewsNotFoundException;
use App\Domain\News\Exceptions\NewsSaveException;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Cache\CacheInterface;
use App\Application\UseCases\News\DTO\NewsDTO;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private UserRepositoryInterface $userRepository,
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

        $isChangedActivityCount = $news->isDraft() !== $command->isDraft;

        $category = $this->categoryRepository->find($command->categoryId);
        if (!$category) {
            throw new \DomainException('Категория не найдена');
        }

        $news->update(
            title: $command->title,
            content: $command->content,
            category: $category,
            thumbnail: $command->thumbnail ?? null
        );

        // Обновляем статус публикации/черновика
        if ($command->isDraft) {
            $news->moveToDraft();
        } else {
            $publishedAt = $command->publishedAt ?: null;
            $news->publish($publishedAt);
        }

        if ($isAdmin && $command->authorId) {
            $author = $this->userRepository->find($command->authorId);
            if ($author) {
                $news->setAuthor($author);
            }
        }

        try {
            $domainNews = $this->newsRepository->save($news);
        } catch (\Exception) {
            throw new NewsSaveException("Не удалось сохранить новость '{$command->title}'");
        }

        $this->cache->flushTagged('news');
        if ($isChangedActivityCount) {
            $this->cache->flushTagged('news_count');
        }

        return new NewsDTO(
            id: $domainNews->getId(),
            title: $domainNews->getTitle(),
            content: $domainNews->getContent(),
            isDraft: $domainNews->isDraft(),
            thumbnail: $domainNews->getThumbnail(),
            createdAt: $domainNews->getCreatedAt(),
            updatedAt: $domainNews->getUpdatedAt(),
            publishedAt: $domainNews->getPublishedAt(),
        );
    }
}
