<?php

namespace App\Application\UseCases\Commands\Project\Create;

use Exception;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use Illuminate\Support\Facades\DB;
use App\Domain\Repositories\Project\DTO\ProjectDTO;
use App\Domain\Repositories\Todo\DTO\TodoStatusDTO;
use App\Domain\Repositories\Project\DTO\ProjectUserDTO;
use App\Domain\Repositories\Todo\DTO\InsertTodoStatusesDTO;
use App\Domain\Repositories\Exceptions\CreateModelFailedException;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
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
                projectId: $projectId,
                userId: $command->userId,
                roles: [ProjectRoleEnum::ADMIN],
                invited: now(),
                joined: now(),
            );

            $this->projectRepository->inviteUser($projectUserDTO);

            $statusDTOs = [
                new TodoStatusDTO(
                    projectId: $projectId,
                    name: 'Новая',
                    sort: 10,
                    color: '#ffc107'
                ),
                new TodoStatusDTO(
                    projectId: $projectId,
                    name: 'В работе',
                    sort: 20,
                    color: '#0dcaf0'
                ),
                new TodoStatusDTO(
                    projectId: $projectId,
                    name: 'Завершена',
                    sort: 30,
                    color: '#198754'
                ),
                new TodoStatusDTO(
                    projectId: $projectId,
                    name: 'Архив',
                    sort: 40,
                    color: '#f8f9fa'
                ),
            ];

            $this->projectRepository->insertTodoStatuses(new InsertTodoStatusesDTO($statusDTOs));

            DB::commit();

            return new Result($projectId);
        } catch (Exception $e) {
            DB::rollBack();
            throw new CreateModelFailedException('Не удалось создать проект');
        }
    }
}
