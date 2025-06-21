<?php

namespace App\Services\UseCases\Commands\Todo\Delete;

use App\Services\Repositories\Todo\TodoRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoRepositoryInterface $repository,
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

        $modelDTO = $this->repository->find($command->todoId, $command->projectId);

        if ($modelDTO) {
            $result = $this->repository->destroy($modelDTO->todoId, $modelDTO->projectId);
        }

        return $result;
    }
}
