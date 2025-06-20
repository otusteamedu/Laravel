<?php

namespace App\Services\Commands\CreateUser;

use App\Models\User;
use App\Repositories\Users\UserRepositoryInterface;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Services\Exceptions\Users\UserEmailAlreadyExistsException;
use App\Services\Exceptions\Users\UserSaveException;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher
    ) {
    }

    public function handle(Command $command): bool
    {
        // Проверяем, существует ли уже пользователь с таким email
        if ($this->userRepository->existsByEmail($command->email)) {
            throw new UserEmailAlreadyExistsException($command->email);
        }

        $user = new User();

        $user->name = $command->name;
        $user->email = $command->email;

        if ($command->password) {
            $user->password = $this->passwordHasher->hash($command->password);
        }

        $result = $this->userRepository->save($user);
        
        if (!$result) {
            throw new UserSaveException("Не удалось сохранить пользователя '{$command->name}'");
        }

        return $result;
    }
} 