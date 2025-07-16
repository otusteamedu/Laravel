<?php

namespace App\Application\UseCases\Commands\Auth\Password\Update;

use App\Domain\Repositories\User\Contracts\UserRepositoryInterface;
use App\Application\UseCases\Commands\Auth\Password\Update\Command;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(Command $command): bool
    {
        return $this->userRepository->passwordUpdate($command->userId, $command->password);
    }
}
