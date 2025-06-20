<?php

namespace App\Repositories\Categories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function fetchAll(): array;

    /**
     * @param int $limit
     * @param int $offset
     * @return Category[]
     */
    public function fetchPaginated(int $limit, int $offset): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function find(int $id): ?Category;

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function save(Category $category): bool;

    /**
     * @param Category $category
     *
     * @return bool|null
     */
    public function delete(Category $category): ?bool;
}
