<?php

namespace App\Services\Queries\FetchPopularCategories;

use App\Infrastructure\Cache\CacheInterface;
use App\Models\Category;
use App\Services\DTO\Categories\CategoriesDTO;
use App\Services\DTO\Categories\CategoryDTO;
use App\Services\Repositories\CategoryRepositoryInterface;

class Fetcher
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CacheInterface $cache
    ) {
    }

    /**
     * @param Query $query
     *
     * @return CategoriesDTO
     */
    public function fetch(Query $query): CategoriesDTO
    {
        $categories = $this->cache->rememberWithTags(['categories', 'news_count'], 'popular_categories_list', env('POPULAR_CATEGORIES_CACHE_TIME', 1800),
            function () use ($query) {
                return $this->categoryRepository->getPopular($query->limit);
            }
        );

        $categoryDTOs = array_map(function (Category $category) {
            return new CategoryDTO(
                id: $category->id,
                name: $category->name,
                slug: $category->slug,
                sort: $category->sort,
                newsCount: $category->news_count ?? null,
            );
        }, $categories);

        return new CategoriesDTO($categoryDTOs);
    }
}
