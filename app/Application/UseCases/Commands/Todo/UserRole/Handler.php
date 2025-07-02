<?php

namespace App\Application\UseCases\Commands\Todo\UserRole;

use App\Events\Todo\UserAssignTodoRoleEvent;
use App\Domain\Repositories\Todo\ValueObject\TodoRoleEnum;
use App\Domain\Repositories\Todo\Contracts\TodoRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoRepositoryInterface $repository,
    ) {
        //
    }

    /**
     * Добавить к задаче участника или измениь его роль
     * @param Command $command
     * @return bool
     */
    public function handle(Command $command): bool
    {
        switch ($command->role) {
            case TodoRoleEnum::RESPONSIBLE:
                $responsibles = $this->repository->fetchUsersByRole($command->todoId, TodoRoleEnum::RESPONSIBLE);

                foreach ($responsibles as $responsible) {
                    $this->repository->saveUser($command->todoId, $responsible->userId, TodoRoleEnum::WATCHER);
                }
                break;
            case TodoRoleEnum::PERFORMER:
                $performers = $this->repository->fetchUsersByRole($command->todoId, TodoRoleEnum::PERFORMER);

                foreach ($performers as $performer) {
                    $this->repository->saveUser($command->todoId, $performer->userId, TodoRoleEnum::WATCHER);
                }
                break;
        }

        $result = $this->repository->saveUser($command->todoId, $command->userId, $command->role);

        UserAssignTodoRoleEvent::dispatch(
            $command->userId,
            $command->projectId,
            $command->todoId,
            $command->role->value
        );

        return $result;
    }
}
