<?php

namespace App\Services\UseCases\Commands\Project\Update;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\Exceptions\ModelNotFoundException;
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
     * @throws ModelNotFoundException
     * @return bool
     */
    public function handle(Command $command): bool
    {
        $modelDTO = $this->projectRepository->find($command->id);

        if ($modelDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $updatedDTO = new ProjectDTO(
            id: $modelDTO->id,
            name: $command->name,
            description: $command->description,
        );

        return $this->projectRepository->save($updatedDTO);
    }
}
