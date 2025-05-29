<?php

namespace App\Infrastructure\Eloquent\Repositories;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Services\Repositories\ProjectDTO;
use App\Services\Repositories\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function fetchForUser(int $userId): array
    {
        $dbProjects = Project::query()
            ->whereHas('projectUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNotNull('joined_at')
                    ->whereNull('left_at');
            })
            ->get();

        return array_map(
            fn($project) =>
            new ProjectDTO(
                id: $project['id'],
                name: $project['name'],
                description: $project['description'],
                created: Carbon::parse($project['created_at']),
            ),
            $dbProjects->toArray()
        );
    }

    public function find(int $id): ?ProjectDTO
    {
        $project = Project::query()
            ->where('id', $id)
            ->first();

        if ($project === null) {
            return null;
        }

        return new ProjectDTO(
            id: $project->id,
            name: $project->name,
            description: $project->description,
            created: $project->created_at,
        );
    }

    public function add(ProjectDTO $project): int
    {
        $dbProject = Project::create([
            'name'        => $project->name,
            'description' => $project->description,
        ]);

        return $dbProject->refresh()->id;
    }

    public function save(ProjectDTO $project): bool
    {
        return Project::query()
            ->where('id', $project->id)
            ->update([
                'name'        => $project->name,
                'description' => $project->description,
            ]);
    }

    public function destroy(int $id): bool
    {
        return Project::where('id', $id,)
            ->delete() ?? false;
    }

    public function fetchUsers(int $projectId): array
    {
        return ProjectUser::query()
            ->with(['users' => function ($query) {
                $query->orderBy('name');
            }])
            ->where('project_id', $projectId)
            ->get()
            ->all();
    }

    public function fetchTodoStatuses(int $projectId): array
    {
        return TodoStatus::query()
            ->where('project_id', $projectId)
            ->orderBy('sort')
            ->get()
            ->all();
    }

    public function userJoun(int $userId): bool
    {
        throw new MethodNotImplimentedException;
    }

    /**
     * Пользователь покинул проект
     */
    public function userLeft(int $userId): bool
    {
        throw new MethodNotImplimentedException;
    }

    /**
     * Все пользователи покинули проект
     */
    public function usersLeft(int $id): bool
    {
        return ProjectUser::query()
            ->where('project_id', $id)
            ->whereNotNull('joined_at')
            ->update(['left_at' => now()]);
    }
}
