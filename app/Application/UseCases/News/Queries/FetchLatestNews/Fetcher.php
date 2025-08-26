<?php

namespace App\Application\UseCases\News\Queries\FetchLatestNews;

use App\Domain\News\Entities\News;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Infrastructure\Cache\CacheInterface;
use App\Application\UseCases\News\DTO\AuthorDTO;
use App\Application\UseCases\News\DTO\CategoryDTO;
use App\Application\UseCases\News\DTO\NewsDTO;
use App\Application\UseCases\News\DTO\ResultDTO;

class Fetcher
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private CacheInterface $cache
    ) {
    }

    /**
     * @param Query $query
     * @return ResultDTO
     */
    public function fetch(Query $query): ResultDTO
    {
        $newsItems = $this->cache->rememberWithTag(
            'news',
            'latest_news_list',
            (int)env('LATEST_NEWS_CACHE_TIME', 900),
            fn () => $this->newsRepository->getLatest($query->limit)
        );

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
        }, $newsItems);

        return new ResultDTO($newsDTOs);
    }
}

