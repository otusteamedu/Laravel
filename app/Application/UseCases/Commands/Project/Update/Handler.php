<?php

namespace App\Application\UseCases\Commands\Project\Update;

use App\Domain\Repositories\Project\DTO\ProjectDTO;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

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
        $result = false;

        $modelDTO = $this->projectRepository->find($command->id);

        if ($modelDTO) {
            $updatedDTO = new ProjectDTO(
                projectId: $modelDTO->projectId,
                name: $command->name,
                description: $command->description,
            );

            $result = $this->projectRepository->save($updatedDTO);
        }

        return $result;
    }
}
