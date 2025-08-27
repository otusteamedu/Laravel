<?php

namespace App\Domain\Category\Services;

use App\Domain\Category\Model\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class CategoryService
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function createCategory(
        string $title,
        ?string $alias,
        ?string $text,
        ?bool $published,
        ?int $order
    ): Category {
        if ($alias === null) {
            $alias = $this->generateAliasFromTitle($title);
        }

        $category = new Category(
            null,
            $title,
            $alias,
            $text,
            $published,
            $order
        );

        if ($this->categoryRepository->existsWithAlias($alias)) {
            throw new \DomainException("Category with alias '{$alias}' already exists");
        }

        $this->categoryRepository->save($category);
        return $category;
    }

    public function updateCategory(
        int $id,
        string $title,
        ?string $alias = null,
        ?string $text = null,
        ?bool $published = null,
        ?int $order = null
    ): Category {

        $category = $this->categoryRepository->findById($id);

        $originalAlias = $category->getAlias();

        $category = new Category(
            $id,
            $title,
            $alias,
            $text,
            $published,
            $order
        );

        $newAlias = $category->getAlias();
        if ($newAlias && $newAlias !== $originalAlias && $this->categoryRepository->existsWithAlias($newAlias, $category->getId())) {
            throw new \DomainException("Category with alias '{$newAlias}' already exists");
        }


        return $this->categoryRepository->save($category);
    }

    public function deleteCategory(int $id): void
    {
        $this->categoryRepository->delete($id);
    }


    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function getCategoryByAlias(string $alias): ?Category
    {
        return $this->categoryRepository->findByAlias($alias);
    }

    /**
     * @return Category[]
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @param array $criteria
     * @return Category[]
     */
    public function searchCategories(array $criteria): array
    {
        return $this->categoryRepository->findByCriteria($criteria);
    }

    public function categoryExistsWithAlias(string $alias, ?int $excludeId = null): bool
    {
        return $this->categoryRepository->existsWithAlias($alias, $excludeId);
    }

    private function generateAliasFromTitle(string $title): string
    {
        $alias = strtolower($title);
        $alias = preg_replace('/[^a-z0-9]+/', '-', $alias);
        $alias = trim($alias, '-');
        return $alias;
    }

    public function getCategoriesPaginated(int $page = 1, int $perPage = 15): array
    {
        return $this->categoryRepository->paginate($page, $perPage);
    }

    public function getTotalCount(): int
    {
        return $this->categoryRepository->count();
    }

}
