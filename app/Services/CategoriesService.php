<?php
namespace App\Services;

use App\Dto\Admin\Category\StoreDto;
use App\Dto\Admin\Category\UpdateDto;
use App\Repositories\CategoriesRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

class CategoriesService
{
    public function __construct(
        private CategoriesRepository $repository,
    ) {}

    /**
     * @return Collection<array-key, Category>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    /**
     * @return Collection<array-key, Category>
     */
    public function getAllWithSort(string $sort, string $direction): Collection
    {
        return $this->repository->fetchAllWithSort($sort, $direction);
    }

    /**
     * @return Category
     */
    public function getById($categoryId): Category
    {
        return $this->repository->find($categoryId);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->repository->count();
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