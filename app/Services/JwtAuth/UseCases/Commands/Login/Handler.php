<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\UseCases\Commands\Login;

use App\Services\JwtAuth\Contracts\AuthServiceInterface;
use App\Services\JwtAuth\Contracts\RefreshTokenRepositoryInterface;
use App\Services\JwtAuth\Contracts\RefreshTokenHasherInterface;
use App\Services\JwtAuth\Exceptions\RefreshTokenInvalidCredentialsException;
use App\Services\JwtAuth\Exceptions\RefreshTokenSaveException;
use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Carbon\Carbon;
class Handler
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
        private RefreshTokenHasherInterface $tokenHasher,
        private AuthServiceInterface $authService,
    ) {}

    public function handle(Command $command): array
    {
        $user = $this->authService->attempt($command->email, $command->password);

        if (! $user ) {
            throw new RefreshTokenInvalidCredentialsException();
        }

        $refreshToken = Str::random(64);
        $refreshTokenModel = new RefreshToken();

        $refreshTokenModel->{$refreshTokenModel->getColumnName('user_id')} = (int)$user['userId'];
        $refreshTokenModel->{$refreshTokenModel->getColumnName('token')} = $this->tokenHasher->hash($refreshToken);
        $refreshTokenModel->{$refreshTokenModel->getColumnName('expires_at')} = Carbon::now()->addMinutes($command->refreshTtl);

        $result = $this->refreshTokenRepository->save($refreshTokenModel);

        if (!$result) {
            throw new RefreshTokenSaveException("Не удалось сохранить токен");
        }

        return [
            'access_token' => $user['token'],
            'token_type' => $this->authService->getTokenType(),
            'expires_in' => $this->authService->getTtl(),
            'refresh_token' => $refreshToken,
        ];
    }
}
