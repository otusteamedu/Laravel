<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\TodoStatus;
use App\Services\Repositories\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function fetchForUser(int $userId): array
    {
        return Project::query()
            ->whereHas('projectUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNotNull('joined_at')
                    ->whereNull('left_at');
            })
            ->get()
            ->all();
    }

    public function find(int $id): ?Project
    {
        return Project::query()
            ->where('id', $id)
            ->first();
    }

    public function add(Project $project): int
    {
        $project->save();
        $project->refresh();

        return $project->id;
    }

    public function save(Project $project): void
    {
        $project->save();
    }

    public function destroy(Project $project): void
    {
        $project->delete();
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
}
