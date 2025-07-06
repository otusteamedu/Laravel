<?php

namespace App\Application\UseCases\News\Commands\CreateNews;

use App\Domain\News\Entities\News as DomainNews;
use App\Domain\News\Entities\Category as DomainCategory;
use App\Domain\User\Entities\User as DomainUser;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\News\Exceptions\NewsSaveException;
use App\Infrastructure\Cache\CacheInterface;
use App\Application\UseCases\News\DTO\NewsDTO;

class Handler
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private UserRepositoryInterface $userRepository,
        private CacheInterface $cache
    ) {
    }

    public function handle(Command $command): NewsDTO
    {
        $author = $command->authorId
            ? $this->userRepository->find($command->authorId)
            : null;
        $category = $command->categoryId
            ? $this->categoryRepository->find($command->categoryId)
            : null;

        if (!$author) {
            throw new \DomainException('Автор не найден');
        }
        if (!$category) {
            throw new \DomainException('Категория не найдена');
        }

        $news = new DomainNews(
            id: null, // или генерируйте UUID, если нужно
            author: $author,
            category: $category,
            title: $command->title,
            content: $command->content,
            publishedAt: $command->publishedAt,
            isDraft: $command->isDraft,
        );

        try {
            $domainNews = $this->newsRepository->save($news);
        } catch (\Exception) {
            throw new NewsSaveException("Не удалось сохранить новость '{$command->title}'");
        }

        $this->cache->flushTagged('news');
        $this->cache->flushTagged('news_count');

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
