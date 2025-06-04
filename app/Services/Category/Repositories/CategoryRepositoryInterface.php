<?php

declare(strict_types=1);

namespace App\Services\Category\Repositories;

use App\Models\Category;
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
    //public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator;

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function find(int $id): ?Category;

	/**
	 * @return Category
	 */
	public function create(): Category;

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

    /**
     * @param string $slug
     *
     * @return Category|null
     */
    public function findBySlug(string $slug): ?Category;


    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array;
}
