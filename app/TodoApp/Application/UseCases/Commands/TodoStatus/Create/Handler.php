<?php

namespace App\TodoApp\Application\UseCases\Commands\TodoStatus\Create;

use App\TodoApp\Domain\ValueObjects\Color;
use App\TodoApp\Domain\ValueObjects\ModelId;
use Exception;
use App\TodoApp\Domain\Models\TodoStatus;
use App\TodoApp\Domain\Exceptions\CreateModelFailedException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\TodoApp\Domain\ValueObjects\TodoStatus as TodoStatusVO;

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

            $model = new TodoStatus(
                id: new ModelId($this->projectRepository->getTodoStatusNextId()),
                todoStatus: new TodoStatusVO(
                    projectId: new ModelId($command->projectId),
                    name: $command->name,
                    sort: $command->sort,
                    color: new Color($command->color)
                )
            );

            $id = $this->projectRepository->addTodoStatus($model);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новый статус');
        }
    }
}
