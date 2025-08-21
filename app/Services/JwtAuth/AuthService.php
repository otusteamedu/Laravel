<?php

declare(strict_types=1);

namespace App\Services\JwtAuth;

use App\Services\JwtAuth\Contracts\AuthServiceInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{

    public function attempt(string $email, string $password): ?array
    {
        $token = JWTAuth::attempt(['email' => $email, 'password' => $password]);

        if ($token === false) {
            return null;
        }

        $user = JWTAuth::user();

        return [
            'token' => $token,
            'userId' => $user->getId(),
        ];
    }

    public function generateAccessToken(JWTSubject $user): string
    {
        return JWTAuth::fromUser($user);
    }

    public function getTtl(): int {
        return auth('api')->factory()->getTTL() * 60;
    }

    public function getTokenType(): string {
        return 'bearer';
    }
}
