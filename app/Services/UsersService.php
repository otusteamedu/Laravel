<?php
namespace App\Services;

use App\Dto\Admin\User\StoreDto;
use App\Dto\Admin\User\UpdateDto;
use App\Dto\User\PasswordDto;
use App\Dto\User\ProfileDto;
use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;

class UsersService
{
    public function __construct(
        private UsersRepository $repository,
    ) {}

    /**
     * @return Collection<array-key, User>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    /**
     * @return LengthAwarePaginator<array-key, User>
     */
    public function getList(string $sort, string $direction, int $page): LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction, $page);
    }

     /**
     * @return User
     */
    public function getById($userId): User
    {
        return $this->repository->find($userId);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->repository->count();
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