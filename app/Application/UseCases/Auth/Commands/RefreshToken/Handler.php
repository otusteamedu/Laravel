<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\RefreshToken;

use App\Domain\Auth\Repositories\RefreshTokenRepositoryInterface;
use App\Infrastructure\RefreshTokenHasher\RefreshTokenHasherInterface;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use DomainException;
use Illuminate\Support\Str;

class Handler
{
    public function __construct(private RefreshTokenRepositoryInterface $refreshTokenRepository, private RefreshTokenHasherInterface $tokenHasher) {}

    public function handle(Command $command): array
    {
        $hashedToken = $this->tokenHasher->hash($command->refreshToken);

        $refreshToken = $this->refreshTokenRepository->findByToken($hashedToken);

        if (!$refreshToken || $refreshToken->isExpired() || $refreshToken->isRevoked()) {
            throw new DomainException('Invalid, revoked or expired refresh token');
        }

        $user = $refreshToken->user;

        $newAccessToken = JWTAuth::fromUser($user);

        $newRefreshToken = Str::random(64);
        $this->refreshTokenRepository->deleteByToken($hashedToken);

        $this->refreshTokenRepository->create([
                                                  'user_id' => $user->id,
                                                  'token' => $this->tokenHasher->hash($newRefreshToken),
                                                  'expires_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl')),
                                              ]);

        return [
            'access_token' => $newAccessToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'refresh_token' => $newRefreshToken,
        ];
    }
}
