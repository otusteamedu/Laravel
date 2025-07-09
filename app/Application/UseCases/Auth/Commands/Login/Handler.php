<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\Login;

use App\Domain\Auth\Repositories\RefreshTokenRepositoryInterface;
use App\Infrastructure\RefreshTokenHasher\RefreshTokenHasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use DomainException;

class Handler
{
    public function __construct(private RefreshTokenRepositoryInterface $refreshTokenRepository, private RefreshTokenHasherInterface $tokenHasher) {}

    public function handle(Command $command): array
    {
        if (! $token = JWTAuth::attempt(['email' => $command->email, 'password' => $command->password])) {
            throw new DomainException('Invalid credentials');
        }

        $user = Auth::user();

        $refreshToken = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(config('jwt.refresh_ttl'));

        $this->refreshTokenRepository->create([
                                                  'user_id' => $user->id,
                                                  'token' => $this->tokenHasher->hash($refreshToken),
                                                  'expires_at' => $expiresAt,
                                              ]);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'refresh_token' => $refreshToken,
        ];
    }
}
