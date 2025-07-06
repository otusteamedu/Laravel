<?php

namespace App\Application\UseCases\News\Queries\FetchAllNews;

use App\Domain\News\Entities\News;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Application\UseCases\News\DTO\AuthorDTO;
use App\Application\UseCases\News\DTO\CategoryDTO;
use App\Application\UseCases\News\DTO\NewsDTO;
use App\Application\UseCases\News\DTO\ResultDTO;

class Fetcher
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
    ) {
    }

    /**
     * @return ResultDTO
     */
    public function fetch(): ResultDTO
    {
        $newsItems = $this->newsRepository->fetchAll();

        if (!is_array($newsItems)) {
            $newsItems = iterator_to_array($newsItems);
        }

        $newsDTOs = array_map(function (News $news) {
            $author = $news->getAuthor();
            $category = $news->getCategory();

            return new NewsDTO(
                id: $news->getId(),
                title: $news->getTitle(),
                content: $news->getContent(),
                isDraft: $news->isDraft(),
                thumbnail: $news->getThumbnail(),
                createdAt: $news->getCreatedAt(),    // \DateTimeInterface или null
                updatedAt: $news->getUpdatedAt(),    // \DateTimeInterface или null
                publishedAt: $news->getPublishedAt(),// \DateTimeInterface или null
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

        return new ResultDTO($newsDTOs);
    }
}

