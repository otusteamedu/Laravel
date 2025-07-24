<?php

declare(strict_types=1);

namespace App\Services\OAuth\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function save(User $user): bool;
}
