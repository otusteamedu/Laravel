<?php

namespace App\Services\UseCases\Commands\TodoStatus\Create;

use Exception;
use App\Models\TodoStatus;
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
        try {

            $model = new TodoStatus;

            $model->project_id = $command->project_id;
            $model->name       = $command->name;
            $model->sort       = $command->sort;
            $model->color      = $command->color;

            $id = $this->repository->add($model);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новый статус');
        }
    }
}
