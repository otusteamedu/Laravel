<?php

namespace App\TodoApp\Application\UseCases\Commands\TodoStatus\Delete;

use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    /**
     * Команда удаления статуса задач для проекта
     * @param Command $command
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $result = false;

        $model = $this->projectRepository->findTodoStatus($command->projectId, $command->statusId);

        if ($model) {
            $result = $this->projectRepository->destroyTodoStatus($command->projectId, $command->statusId);
        }

        return $result;
    }
}
