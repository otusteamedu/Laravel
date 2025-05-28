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

    public function handle(Command $command): void
    {
        $model = $this->repository->findWithProject($command->id, $command->project_id);

        if ($model === null) {
            throw new ModelNotFoundException('Статус для задачи не найден');
        }

        $this->repository->destroy($model);
    }
}
