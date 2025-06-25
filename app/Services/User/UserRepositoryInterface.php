<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function one(int $id): ?User;

    public function oneByEmail(string $email): ?User;

    public function add(User $user): void;

    public function destroy(User $user): void;

    public function update(User $user): void;
}
