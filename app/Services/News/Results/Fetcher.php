<?php
declare(strict_types=1);

namespace App\Services\News\Results;

use App\Models\News;
use App\Services\User\Repositories\UserRepositoryInterface;
use App\Services\Category\Repositories\CategoryRepositoryInterface;

class Fetcher
{

    public function __construct(private UserRepositoryInterface $userRepository, private CategoryRepositoryInterface $categoryRepository)
    {

    }

    /**
     * @param array|News $news
     *
     * @return NewsItemsDTO|NewsDTO
     */
    public function fetch(array|News $news): NewsItemsDTO|NewsDTO
    {
        if (is_array($news)) {
            $userIds = array_map(static fn (News $newsItem) => $newsItem->user_id, $news);
            $categoryIds = array_map(static fn (News $newsItem) => $newsItem->category_id, $news);

            $authors = $this->userRepository->findByIds($userIds);
            $categories = $this->categoryRepository->findByIds($categoryIds);

            $newsDTOs = array_map(fn (News $newsItem) => $this->wrapItem($newsItem, $authors, $categories), $news);

            return new NewsItemsDTO($newsDTOs);
        } else {

            $authors = $news->user_id ? $this->userRepository->findByIds([$news->user_id]) : [];
            $categories = $news->category_id ? $this->categoryRepository->findByIds([$news->category_id]) : [];

            return $this->wrapItem($news, $authors, $categories);
        }
    }


    /**
     * @param News  $news
     * @param array $authors
     * @param array $categories
     *
     * @return NewsDTO
     */
    private function wrapItem(News $news, array $authors, array $categories): NewsDTO {
        return new NewsDTO(
            id: $news->id,
            title: $news->title,
            content: $news->content,
            isDraft: $news->is_draft,
            thumbnail: $news->is_thumbnail,
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
            createdAt: $news->created_at,
            updatedAt: $news->updated_at,
            publishedAt: $news->published_at,
        );
    }
}
