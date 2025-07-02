<?php

namespace App\Services\UseCases\Commands\CreateUser;

use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Models\User;
use App\Services\DTO\Users\UserDTO;
use App\Services\Exceptions\Users\UserEmailAlreadyExistsException;
use App\Services\Exceptions\Users\UserSaveException;
use App\Services\Repositories\UserRepositoryInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function handle(Command $command): UserDTO
    {
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

        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            emailVerifiedAt: $user->email_verified_at,
        );
    }
}
