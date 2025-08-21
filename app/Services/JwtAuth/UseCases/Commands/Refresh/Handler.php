<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\UseCases\Commands\Refresh;

use App\Models\RefreshToken;
use App\Services\JwtAuth\Contracts\RefreshTokenRepositoryInterface;
use App\Services\JwtAuth\Contracts\RefreshTokenHasherInterface;
use App\Services\JwtAuth\Contracts\UserRepositoryInterface as JwtAuthUserRepositoryInterface;
use App\Services\JwtAuth\Exceptions\RefreshTokenNotFoundOrExpiredException;
use App\Services\JwtAuth\Exceptions\RefreshTokenSaveException;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\JwtAuth\Contracts\AuthServiceInterface;
use App\Services\JwtAuth\Exceptions\UserNotFoundException;

class Handler
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
        private RefreshTokenHasherInterface $tokenHasher,
        private AuthServiceInterface $authService,
        private JwtAuthUserRepositoryInterface $userModelRepository,
    ) {}

    public function handle(Command $command): array
    {
        $hashedToken = $this->tokenHasher->hash($command->refreshToken);
        $refreshToken = $this->refreshTokenRepository->findByToken($hashedToken);

        if (!$refreshToken || $refreshToken->isExpired()) {
            throw new RefreshTokenNotFoundOrExpiredException();
        }

        $userId = $refreshToken->getUserId();

        $user = $this->userModelRepository->find($userId);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        $newAccessToken = $this->authService->generateAccessToken($user);

        $newRefreshToken = Str::random(64);
        $this->refreshTokenRepository->deleteByToken($hashedToken);

        $refreshTokenModel = new RefreshToken();

        $refreshTokenModel->{$refreshTokenModel->getColumnName('user_id')} = $userId;
        $refreshTokenModel->{$refreshTokenModel->getColumnName('token')} = $this->tokenHasher->hash($newRefreshToken);
        $refreshTokenModel->{$refreshTokenModel->getColumnName('expires_at')} = Carbon::now()->addMinutes($command->refreshTtl);

        $result = $this->refreshTokenRepository->save($refreshTokenModel);

        if (!$result) {
            throw new RefreshTokenSaveException("Не удалось сохранить токен");
        }

        return [
            'access_token' => $newAccessToken,
            'token_type' => $this->authService->getTokenType(),
            'expires_in' => $this->authService->getTtl(),
            'refresh_token' => $newRefreshToken,
        ];
    }
}
