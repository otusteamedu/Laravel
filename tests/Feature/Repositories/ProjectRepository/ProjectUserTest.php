<?php

namespace Tests\Feature\Repositories\ProjectRepository;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use App\Infrastructure\Eloquent\Repositories\ProjectRepository;

#[Group('repository')]
class ProjectUserTest extends TestCase
{
    protected ProjectRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new ProjectRepository;
    }

    public function test_project_fetch_users(): void
    {
        $countUsers = 5;

        $project = Project::factory()->create();

        $users = User::factory()->count($countUsers)->create();

        foreach ($users as $user) {
            ProjectUser::factory([
                'project_id' => $project->id,
                'user_id'    => $user->id,
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null,
            ])
                ->create();
        }

        $userDTOs = $this->repository->fetchUsers($project->id);

        $this->assertEquals($countUsers, count($userDTOs));
    }

    public function test_project_can_user_invited_and_finded(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $user->id,
            ]), 'projectUsers')
            ->create();

        $finded = $this->repository->findUser($project->id, $user->id);

        $this->assertNotNull($finded);
    }

    public function test_project_can_join_user(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $user->id,
                'invited_at' => now(),
                'joined_at'  => null,
                'left_at'    => null,
            ]), 'projectUsers')
            ->create();

        $success = $this->repository->joinUser($project->id, $user->id);

        $this->assertTrue($success);
    }

    public function test_project_can_left_user(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $user->id,
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null,
            ]), 'projectUsers')
            ->create();

        $success = $this->repository->leftUser($project->id, $user->id);

        $this->assertTrue($success);
    }

    public function test_project_user_has_role(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null,
            ]), 'projectUsers')
            ->create();

        $success = $this->repository->userHasRole($project->id, $user->id, [ProjectRoleEnum::ADMIN]);

        $this->assertTrue($success);
    }

    public function test_project_can_left_all_users(): void
    {
        $countUsers = 5;

        $project = Project::factory()->create();

        $users = User::factory()->count($countUsers)->create();

        foreach ($users as $user) {
            ProjectUser::factory([
                'project_id' => $project->id,
                'user_id'    => $user->id,
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null,
            ])
                ->create();
        }

        $this->repository->leftAllUsers($project->id);

        $userDTOs = $this->repository->fetchUsers($project->id);

        $this->assertEquals(0, count($userDTOs));
    }
}
