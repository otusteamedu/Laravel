<?php

declare(strict_types=1);

namespace App\Services\OAuth;

use App\Services\OAuth\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthService implements AuthServiceInterface
{
    public function attempt(string $email, string $password): ?Authenticatable
    {
        $credentials = compact('email', 'password');

        if (Auth::attempt($credentials)) {
            return Auth::user();
        }

        return null;
    }
}
