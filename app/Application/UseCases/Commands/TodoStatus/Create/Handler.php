<?php

namespace App\Application\UseCases\Commands\TodoStatus\Create;

use Exception;
use Illuminate\Support\Facades\Cache;
use App\Domain\Repositories\Todo\DTO\TodoStatusDTO;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Domain\Repositories\Exceptions\CreateModelFailedException;

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

            Cache::forget("project_{$command->projectId}_todo_statuses");

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новый статус');
        }
    }
}
