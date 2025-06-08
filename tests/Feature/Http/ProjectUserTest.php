<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use App\Infrastructure\Eloquent\Repositories\ProjectRepository;


#[Group('http')]
class ProjectUserTest extends TestCase
{
    protected ProjectRepository $repository;
    protected User $user;
    protected Project $project;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new ProjectRepository;

        $this->user = User::factory()->has(UserProfile::factory(), 'profile')->create();

        $this->project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();
    }

    public function test_project_user_list_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $this->project->id],  false));

        $response->assertOk();
    }

    /*
    public function test_project_can_user_invited_and_finded(): void
    {
        $member = User::factory()->create();

        ProjectUser::factory([
            'project_id' => $this->project->id,
            'user_id'    => $member->id,
            'roles'      => [ProjectRoleEnum::MEMBER],
            'invited_at' => now(),
            'joined_at'  => null,
            'left_at'    => null
        ])
            ->create();

        $finded = $this->repository->findUser($this->project->id, $member->id);

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
*/
}
