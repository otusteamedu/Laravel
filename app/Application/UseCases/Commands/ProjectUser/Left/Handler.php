<?php

namespace App\Application\UseCases\Commands\ProjectUser\Left;

use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Application\UseCases\Commands\ProjectUser\Left\InviteNotFoundException;

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
