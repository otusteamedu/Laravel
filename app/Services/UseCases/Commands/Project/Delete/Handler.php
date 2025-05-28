<?php

namespace App\Services\UseCases\Commands\Project\Delete;

use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\UseCases\Commands\Project\Delete\ModelNotFoundException;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    public function handle(Command $command): void
    {
        $project = $this->projectRepository->find($command->id);

        if ($project === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $this->projectRepository->destroy($project);
    }
}
