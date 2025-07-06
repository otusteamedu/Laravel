<?php

namespace App\Application\UseCases\User\Commands\UpdateUser;

use App\Application\UseCases\User\DTO\UserDTO;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function handle(Command $command): UserDTO
    {
        $user = $this->userRepository->find($command->id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        // Обновляем данные через методы доменной сущности
      /*  $user->update(
            name: $command->name,
            email: $command->email,
            passwordHash: $command->password
                      ? $this->passwordHasher->hash($command->password)
                      : null
        );*/

        // Меняем имя, если оно изменилось
        if ($user->getName() !== $command->name) {
            $user->changeName($command->name);
        }

        // Меняем email, если он изменился
        if ($user->getEmail() !== $command->email) {
            $user->changeEmail($command->email);
        }

        // Меняем пароль, если он передан
        if ($command->password) {
            $user->changePassword($this->passwordHasher->hash($command->password));
        }

        $this->userRepository->save($user);

        return new UserDTO(
            id: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail(),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            emailVerifiedAt: $user->getEmailVerifiedAt(),
        );
    }
}
