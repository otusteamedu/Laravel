<?php

namespace App\TodoApp\Application\UseCases\Commands\Project\Update;

use App\TodoApp\Domain\Exceptions\UpdateModelFailedException;
use App\TodoApp\Domain\Models\Project;
use App\TodoApp\Domain\ValueObjects\ProjectDescription;
use App\TodoApp\Domain\ValueObjects\ProjectName;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use DateTime;
use Exception;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    /**
     * Обновляем данные проекта
     * @param Command $command
     * @return bool
     */
    public function handle(Command $command): bool
    {
        try {

            $project = $this->projectRepository->find($command->id);

            if ($project) {
                $update = new Project(
                    id: $project->getId(),
                    name: new ProjectName($command->name),
                    description: new ProjectDescription($command->description),
                    created: $project->getCreated(),
                    updated: new DateTime()
                );

                $result = $this->projectRepository->save($update);
            }
        } catch (Exception $exception) {
            throw new UpdateModelFailedException('Не удалось обновить проект.' . $exception->getMessage());
        }

        return $result;
    }
}
