<?php

namespace App\Services\UseCases\Commands\Project\Create;

use Exception;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use Illuminate\Support\Facades\DB;
use App\Services\Repositories\ProjectDTO;
use App\Services\Repositories\TodoStatusDTO;
use App\Services\Repositories\InsertTodoStatusesDTO;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\ProjectUserDTO;
use App\Services\Repositories\TodoStatusRepositoryInterface;
use App\Services\Repositories\ProjectUserRepositoryInterface;

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
            $projectDTO = new ProjectDTO(
                name: $command->name,
                description: $command->description,
                created: now(),
            );

            $projectId = $this->projectRepository->add($projectDTO);

            $projectUserDTO = new ProjectUserDTO(
                project_id: $projectId,
                user_id: $command->userId,
                roles: [ProjectRoleEnum::ADMIN],
                invited: now(),
                joined: now(),
            );

            $this->projectUserRepository->add($projectUserDTO);

            $statusDTOs = [
                new TodoStatusDTO(
                    project_id: $projectId,
                    name: 'Новая',
                    sort: 10,
                    color: '#ffc107'
                ),
                new TodoStatusDTO(
                    project_id: $projectId,
                    name: 'В работе',
                    sort: 20,
                    color: '#0dcaf0'
                ),
                new TodoStatusDTO(
                    project_id: $projectId,
                    name: 'Завершена',
                    sort: 30,
                    color: '#198754'
                ),
                new TodoStatusDTO(
                    project_id: $projectId,
                    name: 'Архив',
                    sort: 40,
                    color: '#f8f9fa'
                ),
            ];

            $this->todoStatusRepository->insert(new InsertTodoStatusesDTO($statusDTOs));

            DB::commit();

            return new Result($projectId);
        } catch (Exception $e) {
            DB::rollBack();
            debugbar()->error($e->getMessage());

            throw new CreateModelFailedException('Не удалось создать проект');
        }
    }
}
