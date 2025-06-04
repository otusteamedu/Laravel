<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\Exceptions\ModelNotFoundException;
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
     * @param Command $command
     * @throws ModelNotFoundException
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $modelDTO = $this->repository->find($command->id, $command->project_id);

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
