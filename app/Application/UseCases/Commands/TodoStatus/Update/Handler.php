<?php

namespace App\Application\UseCases\Commands\TodoStatus\Update;

use Illuminate\Support\Facades\Cache;
use App\Domain\Repositories\Todo\DTO\TodoStatusDTO;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

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

        Cache::forget("project_{$command->projectId}_todo_statuses");

        return $result;
    }
}
