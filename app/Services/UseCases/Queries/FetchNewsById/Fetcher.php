<?php

namespace App\Services\UseCases\Queries\FetchNewsById;

use App\Models\News;
use App\Services\DTO\News\AuthorDTO;
use App\Services\DTO\News\CategoryDTO;
use App\Services\DTO\News\NewsDTO;
use App\Services\Exceptions\News\NewsNotFoundException;
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
     * @return NewsDTO
     * @throws NewsNotFoundException
     */
    public function fetch(Query $query): NewsDTO
    {
        /** @var ?News $news */
        $news = $this->newsRepository->find($query->id);

        if (!$news) {
            throw new NewsNotFoundException('Новость не найдена');
        }

        $author = $news->user_id ? $this->userRepository->find($news->user_id) : null;
        $category = $news->category_id ? $this->categoryRepository->find($news->category_id) : null;

        return new NewsDTO(
            id: $news->id,
            title: $news->title,
            content: $news->content,
            isDraft: $news->is_draft,
            thumbnail: $news->is_thumbnail,
            createdAt: $news->created_at,
            updatedAt: $news->updated_at,
            publishedAt: $news->published_at,
            author: !is_null($author) ? new AuthorDTO(
                    id: $author->id,
                    name: $author->name,
                    email: $author->email,
                ) : null,
            category: !is_null($category) ? new CategoryDTO(
                    id: $category->id,
                    name: $category->name,
                    slug: $category->slug,
                ) : null,
        );
    }
}
