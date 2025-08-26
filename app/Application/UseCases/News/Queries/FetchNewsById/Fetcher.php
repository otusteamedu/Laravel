<?php

namespace App\Application\UseCases\News\Queries\FetchNewsById;

use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Application\UseCases\News\DTO\AuthorDTO;
use App\Application\UseCases\News\DTO\CategoryDTO;
use App\Application\UseCases\News\DTO\NewsDTO;
use App\Domain\News\Exceptions\NewsNotFoundException;

class Fetcher
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository
    ) {
    }

    /**
     * @param Query $query
     * @return NewsDTO
     * @throws NewsNotFoundException
     */
    public function fetch(Query $query): NewsDTO
    {
        $news = $this->newsRepository->find($query->id);

        if (!$news) {
            throw new NewsNotFoundException('Новость не найдена');
        }

        $author = $news->getAuthor();
        $category = $news->getCategory();

        return new NewsDTO(
            id: $news->getId(),
            title: $news->getTitle(),
            content: $news->getContent(),
            isDraft: $news->isDraft(),
            thumbnail: $news->getThumbnail(),
            createdAt: $news->getCreatedAt(),
            updatedAt: $news->getUpdatedAt(),
            publishedAt: $news->getPublishedAt(),
            author: $author ? new AuthorDTO(
                    id: $author->getId(),
                    name: $author->getName(),
                    email: $author->getEmail(),
                ) : null,
            category: $category ? new CategoryDTO(
                    id: $category->getId(),
                    name: $category->getName(),
                    slug: method_exists($category, 'getSlug') ? $category->getSlug() : null,
                ) : null,
        );
    }
}


