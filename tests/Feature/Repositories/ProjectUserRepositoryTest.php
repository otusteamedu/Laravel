<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use App\Infrastructure\Eloquent\Repositories\ProjectUserRepository;


#[Group('repository')]
class ProjectUserRepositoryTest extends TestCase
{
    protected ProjectUserRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new ProjectUserRepository;
    }

    public function test_project_user_can_invited_and_finded(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $user->id,
            ]), 'projectUsers')
            ->create();

        $finded = $this->repository->find($project->id, $user->id);

        $this->assertNotNull($finded);
    }

    public function test_project_user_can_join(): void
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

        $success = $this->repository->userJoin($project->id, $user->id);

        $this->assertTrue($success);
    }

    public function test_project_user_can_left(): void
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

        $success = $this->repository->userLeft($project->id, $user->id);

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

        $success = $this->repository->hasRole($project->id, $user->id, [ProjectRoleEnum::ADMIN]);

        $this->assertTrue($success);
    }

    public function test_project_user_fetch(): void
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

    public function test_project_all_user_can_left(): void
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

        $this->repository->usersLeft($project->id);

        $userDTOs = $this->repository->fetchUsers($project->id);

        $this->assertEquals(0, count($userDTOs));
    }
}
