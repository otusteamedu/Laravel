<?php

namespace App\Services\UseCases\Commands\Auth\Register;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Hash;
use App\TodoApp\Application\DTOs\UserCreateDTO;
use App\Services\Repositories\UserRepositoryInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthManager $auth,
    ) {
        //
    }

    /**
     * Регистрация пользователя
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): int
    {
        if ($this->userRepository->findByEmail($command->email)) {
            throw new UserAlreadyRegisteredException('Пользователь с таким email уже зарегистрирован');
        } else {
            $user = new UserCreateDTO(
                name: $command->name,
                email: $command->email,
                password: Hash::make($command->password),
            );

            $userId = $this->userRepository->add($user);
        }

        return $userId;
    }
}
