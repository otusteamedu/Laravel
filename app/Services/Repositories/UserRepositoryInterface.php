<?php

declare(strict_types=1);

namespace App\Services\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @return User
     */
    public function fetchAll(): array;

    public function find(int $id): ?User;

    public function save(User $user): void;

    public function add(User $user): void;
    public function findByEmail(string $email): ?User;
}
