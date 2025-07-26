<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10, string $orderBy = 'order'): LengthAwarePaginator;
    public function find(int $id): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    public function syncCategories(Product $product, array $categoryIds): void;
    public function search(string $query, int $perPage = 10): LengthAwarePaginator;
    public function count(): int;
}
