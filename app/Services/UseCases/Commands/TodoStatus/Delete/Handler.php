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
     * @param \App\Services\UseCases\Commands\TodoStatus\Delete\Command $command
     * @throws \App\Services\UseCases\Commands\TodoStatus\Delete\ModelNotFoundException
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $modelDTO = $this->repository->find($command->id);

        if ($modelDTO === null) {
            throw new ModelNotFoundException('Статус для задачи не найден');
        }

        return $this->repository->destroy($modelDTO->id);
    }
}
