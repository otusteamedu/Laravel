<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\ProjectRepositoryInterface;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    /**
     * Обновляем данные статуса задач для проекта
     * @param Command $command
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $result = false;

        $modelDTO = $this->projectRepository->findTodoStatus($command->projectId, $command->statusId);

        if ($modelDTO) {
            $updatedDTO = new TodoStatusDTO(
                statusId: $modelDTO->statusId,
                projectId: $modelDTO->projectId,
                name: $command->name,
                sort: $command->sort,
                color: $command->color,
            );

            $result = $this->projectRepository->saveTodoStatus($updatedDTO);
        }

        return $result;
    }
}
