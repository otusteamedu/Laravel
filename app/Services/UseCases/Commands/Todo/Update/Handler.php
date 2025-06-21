<?php

namespace App\Services\UseCases\Commands\Todo\Update;

use App\Services\Repositories\Todo\TodoDTO;
use App\Services\Repositories\Todo\TodoRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoRepositoryInterface $repository,
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

        $modelDTO = $this->repository->find($command->todoId, $command->projectId);

        if ($modelDTO) {
            $updatedDTO = new TodoDTO(
                todoId: $command->todoId,
                projectId: $command->projectId,
                title: $command->title,
                description: $command->description,
                deadline: $command->deadline,
                authorId: $command->authorId ?? $modelDTO->author->userId,
                statusId: $command->statusId ?? $modelDTO->status->statusId,
                options: $command->options,
            );

            $result = $this->repository->save($updatedDTO);
        }

        return $result;
    }
}
