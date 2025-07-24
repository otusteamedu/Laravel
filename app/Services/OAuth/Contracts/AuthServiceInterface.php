<?php

declare(strict_types=1);

namespace App\Services\OAuth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthServiceInterface
{
    public function attempt(string $email, string $password): ?Authenticatable;
}
