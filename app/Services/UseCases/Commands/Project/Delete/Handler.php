<?php

namespace App\Services\UseCases\Commands\Project\Delete;

use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\ProjectUserRepositoryInterface;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProjectUserRepositoryInterface $projectUserRepository,
    ) {
        //
    }

    public function handle(Command $command): bool
    {
        $projectDTO = $this->projectRepository->find($command->id);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $this->projectUserRepository->usersLeft($projectDTO->id);

        return $this->projectRepository->destroy($projectDTO->id);
    }
}
