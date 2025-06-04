<?php

namespace App\Services\UseCases\Commands\TodoStatus\Create;

use Exception;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\Exceptions\CreateModelFailedException;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class Handler
{
    public function __construct(
        private TodoStatusRepositoryInterface $repository,
    ) {
        //
    }

    /**
     * Добавление нового статуса для задач проекта
     * @param Command $command
     * @throws CreateModelFailedException
     * @return Result
     */
    public function handle(Command $command): Result
    {
        try {

            $modelDTO = new TodoStatusDTO(
                project_id: $command->project_id,
                name: $command->name,
                sort: $command->sort,
                color: $command->color,
            );

            $id = $this->repository->add($modelDTO);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новый статус');
        }
    }
}
