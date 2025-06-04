<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Repositories\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function fetchAll(): array;

    /**
     * @param string $column
     * @param        $direction
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated( int $perPage = 10): LengthAwarePaginator;

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
