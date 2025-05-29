<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

use App\Services\Repositories\TodoStatusDTO;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoStatusRepositoryInterface $repository,
    ) {
        //
    }

    /**
     * Обновляем данные статуса задач для проекта
     * @param \App\Services\UseCases\Commands\TodoStatus\Update\Command $command
     * @throws \App\Services\UseCases\Commands\TodoStatus\Update\ModelNotFoundException
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $modelDTO = $this->repository->find($command->id);

        if ($modelDTO === null) {
            throw new ModelNotFoundException('Статус не найден');
        }

        $updatedDTO = new TodoStatusDTO(
            id: $modelDTO->id,
            project_id: $modelDTO->project_id,
            name: $command->name,
            sort: $command->sort,
            color: $command->color,
        );

        return $this->repository->save($updatedDTO);
    }
}
