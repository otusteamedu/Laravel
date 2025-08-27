<?php

namespace App\Application\Services;

use App\Domain\Category\Services\CategoryService;
use App\Domain\Category\Model\Category;

class CategoryAppService
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    /**
     * Create a new category
     */
    public function createCategory(
        string $title,
        ?string $alias = null,
        ?string $text = null,
        ?bool $published = true,
        ?int $order = 0
    ): Category {
        return $this->categoryService->createCategory(
            $title,
            $alias,
            $text,
            $published,
            $order
        );
    }

    /**
     * Update an existing category
     */
    public function updateCategory(
        int $id,
        ?string $title = null,
        ?string $alias = null,
        ?string $text = null,
        ?bool $published = null,
        ?int $order = null
    ): Category {
        return $this->categoryService->updateCategory(
            $id,
            $title,
            $alias,
            $text,
            $published,
            $order
        );
    }

    /**
     * Delete a category
     */
    public function deleteCategory(int $id): void
    {
        $this->categoryService->deleteCategory($id);
    }

    /**
     * Get category by ID
     */
    public function getCategoryById(int $id): ?Category
    {
        $category = $this->categoryService->getCategoryById($id);
        return $category;
    }

    /**
     * Get category by alias
     */
    public function getCategoryByAlias(string $alias, bool $withProducts = false): ?Category
    {
        $category = $this->categoryService->getCategoryByAlias($alias);

        if ($category && $withProducts) {
            $products = $this->categoryService->getCategoryProducts($category);
            $category->setProducts($products);
        }

        return $category;
    }

    /**
     * Get all categories
     *
     * @return Category[]
     */
    public function getAllCategories(array $criteria = []): array
    {
        $categories = $this->categoryService->getAllCategories($criteria);

        if (!empty($criteria['with_products'])) {
            foreach ($categories as $category) {
                $products = $this->categoryService->getCategoryProducts($category);
                $category->setProducts($products);
            }
        }

        return $categories;
    }


    /**
     * Get paginated categories
     */
    public function getCategoriesPaginated(int $page = 1, int $perPage = 15, array $criteria = []): array
    {
        $result = $this->categoryService->getCategoriesPaginated($page, $perPage, $criteria);

        return $result;
    }

    /**
     * Search categories by criteria
     *
     * @return Category[]
     */
    public function searchCategories(array $criteria): array
    {
        return $this->categoryService->searchCategories($criteria);
    }


    /**
     * Get total count of categories
     */
    public function getTotalCount(): int
    {
        return $this->categoryService->getTotalCount();
    }


}
