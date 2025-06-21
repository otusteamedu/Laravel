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
        dd($command);
        if ($modelDTO) {
            $updatedDTO = new TodoDTO(
                todoId: $command->todoId,
                title: $command->title,
                authorId: $command->authorId,
                projectId: $command->projectId,
                statusId: $command->statusId,
                description: $command->description,
                deadline: $command->deadline,
                options: $command->options,
            );

            $result = $this->repository->save($updatedDTO);
        }

        return $result;
    }
}
