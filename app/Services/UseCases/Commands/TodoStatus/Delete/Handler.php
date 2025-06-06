<?php

namespace App\Services\UseCases\Commands\TodoStatus\Delete;

use App\Services\Repositories\TodoStatusRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoStatusRepositoryInterface $repository,
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

        $modelDTO = $this->repository->find($command->id, $command->projectId);

        if ($modelDTO) {
            $result = $this->repository->destroy($modelDTO->id, $modelDTO->project_id);
        }

        return $result;
    }
}
