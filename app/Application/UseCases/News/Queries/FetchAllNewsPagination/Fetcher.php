<?php

namespace App\Application\UseCases\News\Queries\FetchAllNewsPagination;

use App\Domain\News\Entities\News;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Application\UseCases\News\DTO\AuthorDTO;
use App\Application\UseCases\News\DTO\CategoryDTO;
use App\Application\UseCases\News\DTO\NewsDTO;
use App\Application\UseCases\News\DTO\PaginatedResult;

class Fetcher
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
    ) {
    }

    /**
     * @param Query $query
     * @return PaginatedResult
     */
    public function fetch(Query $query): PaginatedResult
    {
        $newsItems = $this->newsRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->newsRepository->count();

        $newsDTOs = array_map(function (News $news) {
            $author = $news->getAuthor();
            $category = $news->getCategory();

            return new NewsDTO(
                id: $news->getId(),
                title: $news->getTitle(),
                content: $news->getContent(),
                isDraft: $news->isDraft(),
                thumbnail: $news->getThumbnail(),
                createdAt: $news->getCreatedAt(),      // \DateTimeInterface|null
                updatedAt: $news->getUpdatedAt(),      // \DateTimeInterface|null
                publishedAt: $news->getPublishedAt(),  // \DateTimeInterface|null
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
        }, $newsItems);

        return new PaginatedResult(
            items: $newsDTOs,
            total: $total,
            limit: $query->limit,
            offset: $query->offset
        );
    }
}
