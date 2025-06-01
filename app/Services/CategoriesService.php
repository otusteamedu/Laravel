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

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAll();
    }

    public function getAllWithSort(string $sort, string $direction): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAllWithSort($sort, $direction);
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