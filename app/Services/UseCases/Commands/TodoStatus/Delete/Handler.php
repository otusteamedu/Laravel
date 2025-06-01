<?php

namespace App\Services\UseCases\Commands\TodoStatus\Delete;

use App\Services\Repositories\TodoStatusRepository;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Handler
{
    public function __construct(
        private TodoStatusRepository $repository,
    ) {
        //
    }

    /**
     * Команда удаления статуса задач для проекта
     * @param Command $command
     * @throws ModelNotFoundException
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $modelDTO = $this->repository->find($command->id, $command->projectId);

        if ($modelDTO === null) {
            throw new ModelNotFoundException('Статус для задачи не найден');
        }

        return $this->repository->destroy($modelDTO->id, $modelDTO->project_id);
    }
}
