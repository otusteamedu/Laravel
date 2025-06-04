<?php

namespace App\Services\UseCases\Commands\Project\Delete;

use App\Services\Repositories\Exceptions\ModelNotFoundException;
use App\Services\Repositories\ProjectRepositoryInterface;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    public function handle(Command $command): bool
    {
        $projectDTO = $this->projectRepository->find($command->id);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $this->projectRepository->usersLeft($projectDTO->id);

        return $this->projectRepository->destroy($projectDTO->id);
    }
}
