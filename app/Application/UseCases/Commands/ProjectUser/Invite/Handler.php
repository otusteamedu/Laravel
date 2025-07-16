<?php

namespace App\Application\UseCases\Commands\ProjectUser\Invite;

use Exception;
use App\Domain\Repositories\Project\DTO\ProjectUserDTO;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Domain\Repositories\Exceptions\CreateModelFailedException;

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
     * @throws CreateModelFailedException
     * @return Result
     */
    public function handle(Command $command): Result
    {
        try {
            $member = new ProjectUserDTO(
                projectId: $command->projectId,
                userId: $command->userId,
                roles: $command->roles,
                invited: now()
            );

            $id = $this->repository->inviteUser($member);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось удалось пригласить пользователя');
        }
    }
}
