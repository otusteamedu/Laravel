<?php

namespace App\Services\UseCases\Commands\ProjectUser\Left;

use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\Services\UseCases\Commands\ProjectUser\Left\InviteNotFoundException;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $repository,
    ) {
        //
    }

    /**
     * Добавление нового статуса для задач проекта
     * @param Command $command
     * @throws InviteNotFoundException
     * @return bool
     */
    public function handle(Command $command): bool
    {
        if ($this->repository->findUser($command->projectId, $command->userId)) {
            return $this->repository->leftUser($command->projectId, $command->userId);
        }

        throw new InviteNotFoundException('Пригладение не найдено');
    }
}
