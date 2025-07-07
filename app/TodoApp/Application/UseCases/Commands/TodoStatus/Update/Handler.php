<?php

namespace App\TodoApp\Application\UseCases\Commands\TodoStatus\Update;

use App\TodoApp\Domain\Models\TodoStatus;
use App\TodoApp\Domain\ValueObjects\Color;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\TodoApp\Domain\ValueObjects\TodoStatus as TodoStatusVO;

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

        $model = $this->projectRepository->findTodoStatus($command->projectId, $command->statusId);

        if ($model) {
            $updated = new TodoStatus(
                id: $model->getId(),
                todoStatus: new TodoStatusVO(
                    projectId: $model->getProjectId(),
                    name: $command->name,
                    sort: $command->sort,
                    color: new Color($command->color)
                )
            );

            $result = $this->projectRepository->saveTodoStatus($updated);
        }

        return $result;
    }
}
