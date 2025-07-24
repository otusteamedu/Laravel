<?php

declare(strict_types=1);

namespace App\Services\OAuth\UseCases\Commands\Register;

use App\Models\User;
use App\Infrastructure\PasswordHasher\LaravelPasswordHasher;
use App\Services\OAuth\Contracts\UserRepositoryInterface;
use App\Services\OAuth\Exceptions\UserSaveException;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private LaravelPasswordHasher $passwordHasher,
    ) {}

    public function handle(Command $command): array
    {
        $user = new User();
        $user->{$user->getColumnName('name')} = $command->name;
        $user->{$user->getColumnName('email')} = $command->email;
        $user->{$user->getColumnName('password')} = $this->passwordHasher->hash($command->password);

        $result = $this->userRepository->save($user);

        if (!$result) {
            throw new UserSaveException("Не удалось сохранить токен");
        }

        $token = $user->createToken('authToken')->accessToken;

        return ['token' => $token];
    }
}
