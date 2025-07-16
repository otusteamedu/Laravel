<?php

namespace App\Application\UseCases\Commands\ProjectUser\Join;

use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Application\UseCases\Commands\ProjectUser\Join\InviteNotFoundException;

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
        if ($this->repository->userInvited($command->projectId, $command->userId)) {
            return $this->repository->joinUser($command->projectId, $command->userId);
        }

        throw new InviteNotFoundException('Пригладение не найдено');
    }
}
