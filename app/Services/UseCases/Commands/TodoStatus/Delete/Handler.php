<?php

namespace App\Services\UseCases\Commands\TodoStatus\Delete;

use App\Services\Repositories\ProjectRepositoryInterface;

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

        $modelDTO = $this->projectRepository->findTodoStatus($command->projectId, $command->statusId);

        if ($modelDTO) {
            $result = $this->projectRepository->destroyTodoStatus($modelDTO->projectId, $modelDTO->statusId);
        }

        return $result;
    }
}
