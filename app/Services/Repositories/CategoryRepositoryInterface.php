<?php

declare(strict_types=1);

namespace App\Services\Repositories;

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
     *
     * @return array
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

    public function existsByName(string $name): bool;

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

    /**
     * @param int $limit
     *
     * @return Category[]
     */
    public function getPopular(int $limit): array;
}
