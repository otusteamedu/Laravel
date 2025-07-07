<?php

namespace App\TodoApp\Infrastructure\Eloquent\Repositories;

use DateTime;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\TodoApp\Domain\ValueObjects\Color;
use App\TodoApp\Domain\ValueObjects\ModelId;
use App\TodoApp\Application\DTOs\TodoStatusDTO;
use App\TodoApp\Application\DTOs\ProjectUserDTO;
use App\TodoApp\Domain\ValueObjects\ProjectName;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Application\DTOs\ProjectInvitedUserDTO;
use App\TodoApp\Domain\Models\Project as DomainProject;
use App\TodoApp\Domain\ValueObjects\ProjectDescription;
use App\TodoApp\Infrastructure\Eloquent\Models\Project;
use App\TodoApp\Infrastructure\Eloquent\Models\TodoStatus;
use App\TodoApp\Infrastructure\Eloquent\Models\ProjectUser;
use App\TodoApp\Domain\Exceptions\CreateModelFailedException;
use App\TodoApp\Domain\Models\TodoStatus as DomainTodoStatus;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\TodoApp\Domain\ValueObjects\TodoStatus as TodoStatusVO;


class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Получить максимальный PK Id
     * @return int
     */
    public function getNextId(): int
    {
        return Project::max('id') + 1;
    }

    /**
     * Получить все проекты
     * @return DomainProject[]|null
     */
    public function fetchAll(): ?array
    {
        $dbProjects = Project::query()
            ->get();

        if ($dbProjects === null) {
            return null;
        }

        return array_map(
            fn($project) =>
            new DomainProject(
                id: new ModelId($project->id),
                name: new ProjectName($project->name),
                description: new ProjectDescription($project->description),
                created: $project->created_at,
            ),
            Arr::from($dbProjects)
        );
    }

    /**
     * Получить проект по id
     * @param int $id
     * @return DomainProject|null
     */
    public function find(int $id): ?DomainProject
    {
        $project = Project::query()
            ->where('id', $id)
            ->first();

        if ($project === null) {
            return null;
        }

        return  new DomainProject(
            id: new ModelId($project->id),
            name: new ProjectName($project->name),
            description: new ProjectDescription($project->description),
            created: $project->created_at,

        );
    }

    /**
     * Добавить данные проекта
     * @param DomainProject $project
     * @return int
     */
    public function add(DomainProject $project): int
    {
        DB::beginTransaction();

        try {

            $dbProject = Project::create([
                'name'        => $project->getName()->getValue(),
                'description' => $project->getDescription()->getValue(),
            ]);

            $invitedUsers = $project->getProjectUsers();

            foreach ($invitedUsers as $user) {
                $projectUserDTO = new ProjectUserDTO(
                    projectId: $dbProject->id,
                    userId: $user->getUserId()->getValue(),
                    roles: [ProjectRoleEnum::ADMIN],
                    invited: new DateTime(),
                    joined: new DateTime(),
                );

                $this->inviteUser($projectUserDTO);
            }

            $this->addTodoStatus(
                new DomainTodoStatus(
                    id: new ModelId($this->getTodoStatusNextId()),
                    todoStatus: new TodoStatusVO(
                        projectId: new ModelId($dbProject->id),
                        name: 'Новая',
                        sort: 10,
                        color: new Color('#ffc107')
                    )
                )
            );

            $this->addTodoStatus(
                new DomainTodoStatus(
                    id: new ModelId($this->getTodoStatusNextId()),
                    todoStatus: new TodoStatusVO(
                        projectId: new ModelId($dbProject->id),
                        name: 'В работе',
                        sort: 20,
                        color: new Color('#0dcaf0')
                    )
                )
            );

            $this->addTodoStatus(
                new DomainTodoStatus(
                    id: new ModelId($this->getTodoStatusNextId()),
                    todoStatus: new TodoStatusVO(
                        projectId: new ModelId($dbProject->id),
                        name: 'Завершена',
                        sort: 30,
                        color: new Color('#198754')
                    )
                )
            );

            $this->addTodoStatus(
                new DomainTodoStatus(
                    id: new ModelId($this->getTodoStatusNextId()),
                    todoStatus: new TodoStatusVO(
                        projectId: new ModelId($dbProject->id),
                        name: 'Архив',
                        sort: 40,
                        color: new Color('#f8f9fa')
                    )
                )
            );

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw new CreateModelFailedException('Не удалось добавить проект. ' . $exception->getMessage());
        }

        return $dbProject->refresh()->id;
    }

    /**
     * Обновить данные проекта
     * @param DomainProject $project
     * @return bool
     */
    public function save(DomainProject $project): bool
    {
        return Project::query()
            ->where('id', $project->getId()->getValue())
            ->update([
                'name'        => $project->getName()->getValue(),
                'description' => $project->getDescription()->getValue(),
            ]);
    }

    /**
     * Получить пользователей проекта
     * @param int $projectId
     * @return ProjectInvitedUserDTO[]
     */
    public function destroy(int $projectId): bool
    {
        return Project::where('id', $projectId)
            ->delete() ?? false;
    }

    /**
     * Summary of fetchUsers
     * @param int $projectId
     * @return ProjectInvitedUserDTO[]
     */
    public function fetchUsers(int $projectId): array
    {
        $dbUsers = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereNull('left_at')
            ->join('users', 'users.id', 'project_user.user_id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'project_user.id',
                'project_user.roles',
                'project_user.invited_at',
                'project_user.joined_at',

            )
            ->orderBy('users.name')
            ->get();

        return array_map(
            fn($dbUser) =>
            new ProjectInvitedUserDTO(
                id: $dbUser->id,
                userId: $dbUser->user_id,
                name: $dbUser->name,
                email: $dbUser->email,
                roles: $dbUser->roles,
                invited: $dbUser->invited_at,
                joined: $dbUser->joined_at,
            ),
            Arr::from($dbUsers)
        );
    }

    /**
     * Нaйти запись о пользователе в проекте
     * @param int $projectId
     * @param int $userId
     * @return ProjectUserDTO|null
     */
    public function findUser(int $projectId, int $userId): ?ProjectUserDTO
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->whereNull('left_at')
            ->first();

        if ($dbData === null) {
            return null;
        }

        return new ProjectUserDTO(
            userId: $dbData->user_id,
            projectId: $dbData->project_id,
            roles: $dbData->roles,
            invited: $dbData->invited_at,
            joined: $dbData->joined_at,
            left: $dbData->left_at,
        );
    }

    /**
     * Пользователя пригласили к участию в проекте
     * @param ProjectUserDTO $projectUser
     * @return int
     */
    public function inviteUser(ProjectUserDTO $projectUser): int
    {
        $dbData = ProjectUser::create([
            'user_id'    => $projectUser->userId,
            'project_id' => $projectUser->projectId,
            'roles'      => $projectUser->roles,
            'invited_at' => $projectUser->invited ? Carbon::parse($projectUser->invited) : Carbon::now(),
            'joined_at'  => $projectUser->joined ? Carbon::parse($projectUser->joined) : null,
        ]);

        return $dbData->refresh()->id;
    }

    /**
     * Пользователь вступил в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function joinUser(int $projectId, int $userId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->update(['joined_at' => Carbon::now()]);

        return $updated ? true : false;
    }

    /**
     * Пользователь покинул в проект
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function leftUser(int $projectId, int $userId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->update(['left_at' => Carbon::now()]);

        return $updated ? true : false;
    }

    /**
     * Все пользователи покинули проект
     * @param int $projectId
     * @return bool
     */
    public function leftAllUsers(int $projectId): bool
    {
        $updated = ProjectUser::query()
            ->where('project_id', $projectId)
            ->whereNotNull('joined_at')
            ->update(['left_at' => Carbon::now()]);

        return $updated ? true : false;
    }

    /**
     * Проверяет приглашен ли пользователь к участию в проекте
     * @param int $projectId
     * @param int $userId
     * @return bool
     */
    public function userInvited(int $projectId, int $userId): bool
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->whereNull('left_at')
            ->first();

        return $dbData ? true : false;
    }

    /**
     * Проверяет есть ли у пользователя нужная роль на проекте
     * @param int $projectId
     * @param int $userId
     * @param ProjectRoleEnum[] $roles
     * @return bool
     */
    public function userHasRole(int $projectId, int $userId, array $roles): bool
    {
        $dbData = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id',  $userId)
            ->where(function ($query) use ($roles) {
                $query->whereJsonContains('roles', array_shift($roles)->value);

                foreach ($roles as $role) {
                    $query->orWhereJsonContains('roles', $role->value);
                }
            })
            ->whereNotNull('joined_at')
            ->whereNull('left_at')
            ->first();

        return $dbData ? true : false;
    }

    /**
     * Получить список проектов пользователя
     * @param int $userId
     * @return DomainProject[]
     */
    public function fetchUserProjects(int $userId): array
    {
        $dbProjects = Project::query()
            ->whereHas('projectUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNull('left_at');
            })
            ->with(['projectUsers' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->whereNull('left_at');
            }])
            ->get();

        return array_map(
            fn($project) =>
            new DomainProject(
                id: new ModelId($project['id']),
                name: new ProjectName($project['name']),
                description: new ProjectDescription($project['description']),
                created: Carbon::parse($project['created_at']),
            ),
            $dbProjects->toArray()
        );
    }

    /**
     * Получить максимальный PK Id
     * @return int
     */
    public function getTodoStatusNextId(): int
    {
        return TodoStatus::max('id') + 1;
    }

    /**
     * Получить статус для задач проекта по id
     * @param int $projectId
     * @param int $statusId
     * @return DomainTodoStatus|null
     */
    public function findTodoStatus(int $projectId, int $statusId): ?DomainTodoStatus
    {
        $status = TodoStatus::query()
            ->where('id', $statusId)
            ->where('project_id', $projectId)
            ->first();

        if ($status === null) {
            return null;
        }

        return new DomainTodoStatus(
            id: new ModelId($status->id),
            todoStatus: new TodoStatusVO(
                projectId: new ModelId($status->project_id),
                name: $status->name,
                sort: $status->sort,
                color: new Color($status->color),
            )
        );
    }

    /**
     * Добавить данные статуса для задач проекта
     * @param DomainTodoStatus $status
     * @return int
     */
    public function addTodoStatus(DomainTodoStatus $status): int
    {
        $dbStatus = TodoStatus::create([
            'project_id' => $status->getProjectId()->getValue(),
            'name'       => $status->getName(),
            'sort'       => $status->getSort(),
            'color'      => $status->getColor()->getValue(),
        ]);

        return $dbStatus->refresh()->id;
    }

    /**
     * Обновить данные статуса для задач проекта
     * @param DomainTodoStatus $status
     * @return bool
     */
    public function saveTodoStatus(DomainTodoStatus $status): bool
    {
        debugbar()->info($status);
        $updated = TodoStatus::query()
            ->where('id', $status->getId()->getValue())
            ->where('project_id', $status->getProjectId()->getValue())
            ->update([
                'name'  => $status->getName(),
                'sort'  => $status->getSort(),
                'color' => $status->getColor()->getValue(),
            ]);
        debugbar()->info($updated);

        return $updated ? true : false;
    }

    /**
     * Удалить статус задач для проекта
     * @param int $projectId
     * @param int $statusId
     * @return bool
     */
    public function destroyTodoStatus(int $projectId, int $statusId): bool
    {
        return TodoStatus::query()
            ->where('id', $statusId)
            ->where('project_id', $projectId)
            ->delete() ?? false;
    }

    /**
     * Список доступных для задач проекта статусов
     * @param int $projectId
     * @return DomainTodoStatus[]
     */
    public function fetchTodoStatuses(int $projectId): array
    {
        $dbStatuses = TodoStatus::query()
            ->where('project_id', $projectId)
            ->orderBy('sort')
            ->get();

        return array_map(
            fn($status) =>
            new DomainTodoStatus(
                id: new ModelId($status['id']),
                todoStatus: new TodoStatusVO(
                    projectId: new ModelId($status['project_id']),
                    name: $status['name'],
                    sort: $status['sort'],
                    color: new Color($status['color']),
                ),
            ),
            $dbStatuses->toArray()
        );
    }
}
