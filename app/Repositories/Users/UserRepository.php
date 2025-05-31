<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    /**
     * @return array
     */
    public function fetchAll(): array {
        return $this->user::all()->all();
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator {
        return $this->user->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User {
        return $this->user->find($id);
    }

    /**
     * @return User
     */
    public function create(): User {
        return $this->user;
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