<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array;

    /**
     * @param int $limit
     * @param int $offset
     * @return User[]
     */
    public function fetchPaginated(int $limit, int $offset): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User;

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool;

    /**
     * @param User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool;
} 