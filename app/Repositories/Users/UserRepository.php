<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array {
        return User::all()->all();
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator {
        return User::orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User {
        return User::find($id);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool {
        return $user->save();
    }

    /**
     * @param User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool {
        return $user->delete();
    }
} 