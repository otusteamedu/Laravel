<?php


namespace App\Services;

use App\Models\User;

interface UsersRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array;

    public function find(int $id): ?User;

    public function save(User $news): void;

    public function add(User $news): void;

    /**
     * @return User[]
     */
    public function fetchByAuthor(int $authorId): array;

    /**
     * @param int[] $ids
     * @return User[]
     */
    public function findByIds(array $ids): array;
}