<?php

namespace App\Application\UseCases\User\Commands\CreateUser;

use App\Application\Contracts\PasswordHasherInterface;
use App\Application\UseCases\User\DTO\UserDTO;
use App\Domain\User\Entities\User as DomainUser;
use App\Domain\User\Exceptions\UserEmailAlreadyExistsException;
use App\Domain\User\Exceptions\UserSaveException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Roles;

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

        $user = new DomainUser(
            id: null, // id присвоит база данных
            name: $command->name,
            email: $command->email,
            password: $this->passwordHasher->hash($command->password),
            roles: new Roles($command->roles),
            //permissions: new Permissions($command->permissions),
            createdAt: null,
            updatedAt: null,
            emailVerifiedAt: null,
        );

        try {
            $domainUser = $this->userRepository->save($user);
        } catch (\Exception) {
            throw new UserSaveException("Не удалось сохранить пользователя '{$command->name}'");
        }

        return new UserDTO(
            id: $domainUser->getId(),
            name: $domainUser->getName(),
            email: $domainUser->getEmail(),
            roles: $domainUser->getRoles()->roles,
            //permissions: $domainUser->getPermissions()->permissions,
            createdAt: $domainUser->getCreatedAt(),
            updatedAt: $domainUser->getUpdatedAt(),
            emailVerifiedAt: $domainUser->getEmailVerifiedAt(),
            subscribedNews: $domainUser->getSubscribedNews(),
        );
    }
}

