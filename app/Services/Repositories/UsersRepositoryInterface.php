<?php

namespace App\Services\Repositories;

use App\Models\User;

interface UsersRepositoryInterface
{
    public function fetchAll(): array;

    public function find(int $id): ?User;

    public function save(User $User): void;

    public function add(User $User): void;
}
