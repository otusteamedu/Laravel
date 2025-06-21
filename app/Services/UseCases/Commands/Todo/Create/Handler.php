<?php

namespace App\Services\UseCases\Commands\Todo\Create;

use Exception;
use App\Services\Repositories\Todo\TodoDTO;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\Repositories\Exceptions\CreateModelFailedException;

class Handler
{
    public function __construct(
        private TodoRepositoryInterface $repository,
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
            $modelDTO = new TodoDTO(
                title: $command->title,
                authorId: $command->authorId,
                projectId: $command->projectId,
                statusId: $command->statusId,
                description: $command->description,
                deadline: $command->deadline,
                options: $command->options,
            );

            $id = $this->repository->add($modelDTO);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новый статус');
        }
    }
}
