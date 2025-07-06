<?php

declare(strict_types=1);

namespace App\Domain\News\Repositories;

use App\Domain\News\Entities\Category as DomainCategory;
use App\Models\Category as EloquentCategory;

interface CategoryRepositoryInterface
{
    /**
     * @return DomainCategory[]
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
     * @return DomainCategory|null
     */
    public function find(int $id): ?DomainCategory;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsByName(string $name): bool;

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function existsBySlug(string $slug): bool;

    /**
     * @param DomainCategory $category
     *
     * @return DomainCategory
     */
    public function save(DomainCategory $category): DomainCategory;

    /**
     * @param DomainCategory $category
     *
     * @return bool|null
     */
    public function delete(DomainCategory $category): ?bool;

    /**
     * @param string $slug
     *
     * @return DomainCategory|null
     */
    public function findBySlug(string $slug): ?DomainCategory;


    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array;

    /**
     * @param int $limit
     *
     * @return EloquentCategory[]
     */
    public function getPopular(int $limit): array;
}
