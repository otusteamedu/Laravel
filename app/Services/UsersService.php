<?php
namespace App\Services;

use App\Dto\User\StoreDto;
use App\Dto\User\UpdateDto;
use App\Repositories\UsersRepository;

class UsersService
{
    public function __construct(
        private UsersRepository $repository,
    ) {}

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->fetchAll();
    }

    public function getList(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->fetchList();
    }

    public function getById($userId): \App\Models\User
    {
        return $this->repository->find($userId);
    }

    public function delete($userId): void
    {
        $this->repository->delete($userId);
    }

    public function add(StoreDto $storeDto): void
    {
        $this->repository->add($storeDto);
    }

    public function update(UpdateDto $updateDto): void
    {
        $this->repository->save($updateDto);
    }
}