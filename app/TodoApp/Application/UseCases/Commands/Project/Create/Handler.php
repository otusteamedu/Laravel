<?php

namespace App\TodoApp\Application\UseCases\Commands\Project\Create;

use App\TodoApp\Domain\ValueObjects\Email;
use DateTime;
use Exception;
use App\TodoApp\Domain\Models\Project;
use App\TodoApp\Domain\ValueObjects\ModelId;
use App\TodoApp\Domain\ValueObjects\ProjectName;
use App\TodoApp\Domain\ValueObjects\ProjectUser;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Domain\ValueObjects\ProjectDescription;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Domain\Repositories\UserRepositoryInterface;
use App\TodoApp\Domain\Exceptions\CreateModelFailedException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\TodoApp\Domain\ValueObjects\UserName;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private UserRepositoryInterface $useeRepository,
    ) {
        //
    }

    public function handle(Command $command): Result
    {
        $id = $this->projectRepository->getNextId();

        $project = new Project(
            id: new ModelId($id),
            name: new ProjectName($command->name),
            description: new ProjectDescription($command->description),
            created: new DateTime(),
            updated: new DateTime(),
        );

        $admin = $this->useeRepository->find($command->userId);

        if (!$admin) {
            throw new ModelNotFoundException('Не найден пользователь для назначения на роль администратора проекта');
        }

        $inviteUser = new ProjectUser(
            userId: new ModelId($admin->userId),
            name: new UserName($admin->name),
            email: new Email($admin->email),
            roles: [ProjectRoleEnum::ADMIN],
            invited: new DateTime(),
            joined: new DateTime(),
        );

        $project->inviteUser($inviteUser);

        $projectId = $this->projectRepository->add($project);

        return new Result($projectId);
    }
}
