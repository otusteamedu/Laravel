<?php

declare(strict_types=1);

namespace App\Services\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array;

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function fetchPaginated(int $limit, int $offset): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param string $email
     *
     * @return bool
     */
    public function existsByEmail(string $email): bool;
    /**
     * @param int $id
     *
     * @return User|null
     */
    public function find(int $id): ?User;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function save(User $user): bool;

    /**
     * @param User $user
     *
     * @return bool|null
     */
    public function delete(User $user): ?bool;

    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array;

    /**
     * @return User[]
     */
    public function findSubscribedNews(): array;
}
