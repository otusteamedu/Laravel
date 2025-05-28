<?php

namespace App\Services\UseCases\Commands\Project\Create;

use App\Models\Project;
use App\Models\ProjectRoleEnum;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\DB;
use App\Services\Repositories\TodoStatusDTO;
use App\Services\Repositories\InsertTodoStatusesDTO;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\ProjectUserRepositoryInterface;
use App\Services\Repositories\TodoStatusRepositoryInterface;
use Exception;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private TodoStatusRepositoryInterface $todoStatusRepository,
        private ProjectUserRepositoryInterface $projectUserRepository,
    ) {
        //
    }

    public function handle(Command $command): Result
    {
        DB::beginTransaction();

        try {
            $project = new Project;

            $project->name        = $command->name;
            $project->description = $command->description;

            $projectId = $this->projectRepository->add($project);

            $projectUser = new ProjectUser;

            $projectUser->project_id = $projectId;
            $projectUser->user_id    = $command->userId;
            $projectUser->roles      = [ProjectRoleEnum::ADMIN];
            $projectUser->invited_at = now();
            $projectUser->joined_at  = now();

            $this->projectUserRepository->add($projectUser);

            $statusDTOs = [
                new TodoStatusDTO(
                    id: null,
                    project_id: $projectId,
                    name: 'Новая',
                    sort: 10,
                    color: '#ffc107'
                ),
                new TodoStatusDTO(
                    id: null,
                    project_id: $projectId,
                    name: 'В работе',
                    sort: 20,
                    color: '#0dcaf0'
                ),
                new TodoStatusDTO(
                    id: null,
                    project_id: $projectId,
                    name: 'Завершена',
                    sort: 30,
                    color: '#198754'
                ),
                new TodoStatusDTO(
                    id: null,
                    project_id: $projectId,
                    name: 'Архив',
                    sort: 40,
                    color: '#f8f9fa'
                ),
            ];

            $this->todoStatusRepository->insert(new InsertTodoStatusesDTO($statusDTOs));

            DB::commit();

            return new Result($projectId);
        } catch (Exception) {
            DB::rollBack();

            throw new CreateModelFailedException('Не удалось создать проект');
        }
    }
}
