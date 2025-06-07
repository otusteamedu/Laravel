<?php

namespace App\Services\UseCases\Commands\TodoStatus\Create;

use Exception;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Exceptions\CreateModelFailedException;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
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
                projectId: $command->projectId,
                name: $command->name,
                sort: $command->sort,
                color: $command->color,
            );

            $id = $this->projectRepository->addTodoStatus($modelDTO);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новый статус');
        }
    }
}
