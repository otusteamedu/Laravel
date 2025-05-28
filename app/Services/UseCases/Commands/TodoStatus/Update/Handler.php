<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

use App\Services\Repositories\TodoStatusRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoStatusRepositoryInterface $repository,
    ) {
        //
    }

    public function handle(Command $command): Result
    {
        $model = $this->repository->findWithProject($command->id, $command->project_id);

        if ($model === null) {
            throw new ModelNotFoundException('Статус не найден');
        }

        $model->name       = $command->name;
        $model->sort       = $command->sort;
        $model->color      = $command->color;

        $this->repository->save($model);

        return new Result($model->id);
    }
}
