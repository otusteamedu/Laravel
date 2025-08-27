<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Model\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function findByAlias(string $alias): ?Product;
    public function findAll(): array;

    /**
     * @param array $criteria
     * @return Product[]
     */
    public function findByCriteria(array $criteria): array;

    public function save(Product $product): Product;
    public function delete(int $productId): void;

    /**
     * @param int $page
     * @param int $perPage
     * @return array{data: Product[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function paginate(int $page = 1, int $perPage = 15): array;

    public function existsWithAlias(string $alias, ?int $excludeId = null): bool;
}
