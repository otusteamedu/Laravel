<?php

namespace App\Application\UseCases\Commands\Todo\Create;

use Exception;
use App\Domain\Repositories\Todo\DTO\TodoDTO;
use App\Domain\Repositories\Todo\ValueObject\TodoRoleEnum;
use App\Domain\Repositories\Todo\Contracts\TodoRepositoryInterface;
use App\Domain\Repositories\Exceptions\CreateModelFailedException;

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

            $this->repository->saveUser($id, $command->authorId, TodoRoleEnum::PERFORMER);

            return new Result($id);
        } catch (Exception) {
            throw new CreateModelFailedException('Не удалось добавить новую задачу');
        }
    }
}
