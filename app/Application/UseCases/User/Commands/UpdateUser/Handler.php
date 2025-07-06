<?php

namespace App\Application\UseCases\User\Commands\UpdateUser;

use App\Application\UseCases\User\DTO\UserDTO;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserSaveException;
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

        try {
            $domainUser = $this->userRepository->save($user);
        } catch (\Exception) {
            throw new UserSaveException("Не удалось сохранить пользователя '{$command->name}'");
        }

        return new UserDTO(
            id: $domainUser->getId(),
            name: $domainUser->getName(),
            email: $domainUser->getEmail(),
            createdAt: $domainUser->getCreatedAt(),
            updatedAt: $domainUser->getUpdatedAt(),
            emailVerifiedAt: $domainUser->getEmailVerifiedAt(),
            subscribedNews: $domainUser->getSubscribedNews(),
            roles: method_exists($domainUser, 'getRoles') ? $domainUser->getRoles()->roles : [],
           // permissions: method_exists($domainUser, 'getPermissions') ? $domainUser->getPermissions()->permissions : [],
        );
    }
}
