<?php

namespace App\TodoApp\Application\UseCases\Commands\Project\Delete;

use App\TodoApp\Domain\Exceptions\DeleteModelFailedException;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Domain\Exceptions\UpdateModelFailedException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    public function handle(Command $command): bool
    {
        $project = $this->projectRepository->find($command->id);

        if (!$project) {
            throw new ModelNotFoundException('Проект не найден');
        }

        if (!$this->projectRepository->leftAllUsers($project->getId()->getValue())) {
            throw new DeleteModelFailedException('Не удалось удалить из проекта всех пользователей');
        }

        $result = $this->projectRepository->destroy($project->getId()->getValue());

        return $result;
    }
}
