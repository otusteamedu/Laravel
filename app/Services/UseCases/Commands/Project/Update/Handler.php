<?php

namespace App\Services\UseCases\Commands\Project\Update;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\ProjectRepositoryInterface;

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
                id: $modelDTO->id,
                name: $command->name,
                description: $command->description,
            );

            $result = $this->projectRepository->save($updatedDTO);
        }

        return $result;
    }
}
