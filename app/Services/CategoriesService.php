<?php
namespace App\Services;

use App\Dto\Category\StoreDto;
use App\Dto\Category\UpdateDto;
use App\Repositories\CategoriesRepository;

class CategoriesService
{
    public function __construct(
        private CategoriesRepository $repository,
    ) {}

    public function getAll(string $sort, string $direction): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAll($sort, $direction);
    }

    public function getById($categoryId): \App\Models\Category
    {
        return $this->repository->find($categoryId);
    }

    public function update(UpdateDto $updateDto): void
    {
        $this->repository->save($updateDto);
    }

    public function add(StoreDto $storeDto): void
    {
        $this->repository->add($storeDto);
    }

    public function delete($categoryId): void
    {
        $this->repository->delete($categoryId);
    }
}