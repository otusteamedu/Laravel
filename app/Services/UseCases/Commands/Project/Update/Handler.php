<?php

namespace App\Services\UseCases\Commands\Project\Update;

use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\UseCases\Commands\Project\Update\ModelNotFoundException;
use App\Services\UseCases\Commands\Project\Update\Result;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
        //
    }

    /**
     * Summary of handle
     * @param \App\Services\UseCases\Commands\Project\Update\Command $command
     * @throws \App\Services\UseCases\Commands\Project\Update\ModelNotFoundException
     * @return Result
     */
    public function handle(Command $command): Result
    {
        $project = $this->projectRepository->find($command->id);

        if ($project === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $project->name        = $command->name;
        $project->description = $command->description;

        $this->projectRepository->save($project);

        return new Result($project->id);
    }
}
