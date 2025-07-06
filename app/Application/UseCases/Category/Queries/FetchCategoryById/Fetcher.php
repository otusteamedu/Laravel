<?php

namespace App\Application\UseCases\Category\Queries\FetchCategoryById;

use App\Application\UseCases\Category\DTO\CategoryDTO;
use App\Domain\News\Exceptions\CategoryNotFoundException;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\News\Entities\Category;

class Fetcher
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * @param Query $query
     * @return CategoryDTO
     * @throws CategoryNotFoundException
     */
    public function fetch(Query $query): CategoryDTO
    {
        /** @var ?Category $category */
        $category = $this->categoryRepository->find($query->id);

        if (!$category) {
            throw new CategoryNotFoundException('Категория не найдена');
        }

        return new CategoryDTO(
            id: $category->getId(),
            name: $category->getName(),
            slug: $category->getSlug(),
            isActive: $category->getIsActive(),
            sort: $category->getSort(),
            newsCount: method_exists($category, 'getNewsCount')
                    ? $category->getNewsCount()
                    : (property_exists($category, 'news_count') ? $category->news_count : null)
        );
    }
}
