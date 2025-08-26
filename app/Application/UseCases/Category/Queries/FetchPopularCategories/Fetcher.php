<?php

namespace App\Application\UseCases\Category\Queries\FetchPopularCategories;

use App\Application\UseCases\Category\DTO\ResultDTO;
use App\Application\UseCases\Category\DTO\CategoryDTO;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Cache\CacheInterface;
use App\Infrastructure\Eloquent\Repositories\Categories\CategoryMapper;

class Fetcher
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CacheInterface $cache
    ) {
    }

    public function fetch(Query $query): ResultDTO
    {
        $categories = $this->cache->rememberWithTags(
            ['categories', 'news_count'],
            'popular_categories_list',
            (int)env('POPULAR_CATEGORIES_CACHE_TIME', 1800),
            function () use ($query) {
                return $this->categoryRepository->getPopular($query->limit);
            }
        );

        $categoryDTOs = array_map(function($model) {
            $entity = CategoryMapper::toEntity($model);
            return new CategoryDTO(
                id: $entity->getId(),
                name: $entity->getName(),
                slug: $entity->getSlug(),
                isActive: $entity->isActive(),
                sort: $entity->getSort(),
                newsCount: $model->news_count ?? null
            );
        }, $categories);


        return new ResultDTO($categoryDTOs);
    }
}

