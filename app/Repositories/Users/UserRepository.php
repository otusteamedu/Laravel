<?php

namespace App\Repositories\Users;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array {
        return User::all()->all();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return User[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        return User::orderBy('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->all();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return User::count();
    }

    /**
     * @param string $email
     * @return bool
     */
    public function existsByEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
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