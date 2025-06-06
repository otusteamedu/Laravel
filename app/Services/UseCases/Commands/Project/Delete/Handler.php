<?php

namespace App\Services\UseCases\Commands\Project\Delete;

use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\ProjectUserRepositoryInterface;

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
        $result = false;

        $projectDTO = $this->projectRepository->find($command->id);

        if ($projectDTO) {
            $this->projectUserRepository->usersLeft($projectDTO->id);

            $result = $this->projectRepository->destroy($projectDTO->id);
        }

        return $result;
    }
}
