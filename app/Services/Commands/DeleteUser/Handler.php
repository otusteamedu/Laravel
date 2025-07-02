<?php

namespace App\Services\Commands\DeleteUser;

use App\Services\Exceptions\Users\UserNotFoundException;
use App\Services\Repositories\UserRepositoryInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $user = $this->userRepository->find($command->id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return $this->userRepository->delete($user);
    }
}
