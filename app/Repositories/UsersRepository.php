<?php

namespace App\Repositories;

use App\Dto\User\StoreDto;
use App\Dto\User\UpdateDto;
use App\Exceptions\UserNotFoundException;
use App\Models\User;

class UsersRepository
{
    const USERS_PER_PAGE = 10;
    
    public function fetchAll(): \Illuminate\Database\Eloquent\Collection
    {
        return User::all();
    }

    public function fetchList(string $sort, string $direction): \Illuminate\Pagination\LengthAwarePaginator
    {
        return User::orderBy($sort, $direction)->paginate(self::USERS_PER_PAGE)->withQueryString();
    }

    public function find(int $userId): User
    {
        $user = User::with(['role'])->find($userId);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function add(StoreDto $storeDto): void
    {
        $user = new User();
        $user->name = $storeDto->name;
        $user->email = $storeDto->email;
        $user->password = $storeDto->password_hash;
        $user->role_id = $storeDto->role_id;
        $user->save();
    }

    public function save(UpdateDto $updateDto): void
    {
        $user = User::find($updateDto->id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->name = $updateDto->name;
        $user->email = $updateDto->email;
        $user->role_id = $updateDto->role_id;
        $user->save();
    }

    public function delete(int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            throw new UserNotFoundException();
        }
        
        $user->delete();
    }
}