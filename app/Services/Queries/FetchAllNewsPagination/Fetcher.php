<?php

namespace App\Services\Queries\FetchAllNewsPagination;

use App\Models\News;
use App\Services\DTO\News\AuthorDTO;
use App\Services\DTO\News\CategoryDTO;
use App\Services\DTO\News\NewsDTO;
use App\Services\DTO\News\PaginatedResult;
use App\Services\Repositories\CategoryRepositoryInterface;
use App\Services\Repositories\NewsRepositoryInterface;
use App\Services\Repositories\UserRepositoryInterface;


class Fetcher
{
    /**
     * @param NewsRepositoryInterface     $newsRepository
     * @param UserRepositoryInterface     $userRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private UserRepositoryInterface $userRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * @param Query $query
     *
     * @return PaginatedResult
     */
    public function fetch(Query $query): PaginatedResult
    {
        $newsItems = $this->newsRepository->fetchPaginated($query->limit, $query->offset);
        $total = $this->newsRepository->count();

        $userIds = array_map(static fn (News $newsItem) => $newsItem->user_id, $newsItems);
        $categoryIds = array_map(static fn (News $newsItem) => $newsItem->category_id, $newsItems);

        $authors = $this->userRepository->findByIds($userIds);
        $categories = $this->categoryRepository->findByIds($categoryIds);

        $newsDTOs = array_map(function (News $news) use ($authors, $categories) {
            return new NewsDTO(
                id: $news->id,
                title: $news->title,
                content: $news->content,
                isDraft: $news->is_draft,
                thumbnail: $news->is_thumbnail,
                createdAt: $news->created_at,
                updatedAt: $news->updated_at,
                publishedAt: $news->published_at,
                author: isset($authors[$news->user_id]) ? new AuthorDTO(
                        id: $authors[$news->user_id]->id,
                        name: $authors[$news->user_id]->name,
                        email: $authors[$news->user_id]->email,
                    ) : null,
                category: isset($categories[$news->category_id]) ? new CategoryDTO(
                        id: $categories[$news->category_id]->id,
                        name: $categories[$news->category_id]->name,
                        slug: $categories[$news->category_id]->slug,
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
