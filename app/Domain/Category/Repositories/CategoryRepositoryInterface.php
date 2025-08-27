<?php

namespace App\Domain\Category\Repositories;

use App\Domain\Category\Model\Category;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;
    public function findByAlias(string $alias): ?Category;
    public function findAll(): array;

    /**
     * @param array $criteria
     * @return Category[]
     */
    public function findByCriteria(array $criteria): array;

    public function save(Category $category): Category;
    public function delete(int $categoryId): void;
    public function existsWithAlias(string $alias, ?int $excludeId = null): bool;
    public function count(): int;

    /**
     * @param int $page
     * @param int $perPage
     * @return array{data: Category[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function paginate(int $page = 1, int $perPage = 15): array;
}
