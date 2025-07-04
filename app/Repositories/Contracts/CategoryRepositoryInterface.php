<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10, string $orderBy = 'order'): LengthAwarePaginator;
    public function getAll(): Collection;
    public function find(int $id): ?Category;
    public function create(array $data): Category;
    public function update(Category $category, array $data): Category;
    public function delete(Category $category): bool;

}
