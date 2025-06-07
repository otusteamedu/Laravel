<?php

namespace App\Services\UseCases\Commands\Project\Delete;

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
        $result = false;

        $projectDTO = $this->projectRepository->find($command->id);

        if ($projectDTO) {
            $this->projectRepository->leftAllUsers($projectDTO->projectId);

            $result = $this->projectRepository->destroy($projectDTO->projectId);
        }

        return $result;
    }
}
