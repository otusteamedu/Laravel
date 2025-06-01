<?php
namespace App\Services;

use App\Dto\Admin\User\StoreDto;
use App\Dto\Admin\User\UpdateDto;
use App\Dto\User\PasswordDto;
use App\Dto\User\ProfileDto;
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

    public function getList(string $sort, string $direction): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction);
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

    public function updateProfile(ProfileDto $profileDto): void
    {
        $this->repository->saveProfile($profileDto);
    }

    public function updatePassword(PasswordDto $passwordDto): void
    {
        $this->repository->savePassword($passwordDto);
    }
}