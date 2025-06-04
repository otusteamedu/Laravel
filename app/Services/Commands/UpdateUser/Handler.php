<?php

namespace App\Services\Commands\UpdateUser;

use App\Repositories\Users\UserRepositoryInterface;
use App\Services\Exceptions\Users\UserNotFoundException;
use App\Services\DTO\Users\UserDTO;
use Illuminate\Support\Facades\Hash;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(Command $command): UserDTO
    {
        $user = $this->userRepository->find($command->id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        $user->name = $command->name;
        $user->email = $command->email;

        if ($command->password) {
            $user->password = Hash::make($command->password);
        }

        $this->userRepository->save($user);

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