<?php

declare(strict_types=1);

namespace App\Services\User\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array;

    /**
     * @param string $column
     * @param        $direction
     *
     * @return LengthAwarePaginator
     */
    //public function fetchAllPaginate(string $column = 'id', $direction = 'asc', int $perPage = 10): LengthAwarePaginator;

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function find(int $id): ?User;

	/**
	 * @return User
	 */
	public function create(): User;

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
}
