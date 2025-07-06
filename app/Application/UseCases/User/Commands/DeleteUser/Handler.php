<?php
namespace App\Application\UseCases\User\Commands\DeleteUser;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;

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

