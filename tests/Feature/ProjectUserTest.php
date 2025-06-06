<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;


#[Group('projectUser')]
class ProjectUserTest extends TestCase
{
    protected function &getUser()
    {
        static $user = null;
        return $user;
    }

    protected function &getProject()
    {
        static $project = null;
        return $project;
    }

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        if (!$user) {
            $user = User::query()->inRandomOrder()->first();
        }

        if (!$user) {
            $user = User::factory()->has(UserProfile::factory(), 'profile')->create();
        }

        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();
    }

    public function test_project_user_list_is_displayed(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('project.users.index', ['projectId' => $project->id],  false));

        $response->assertOk();
    }

    public function test_project_user_list_not_found(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('project.users.index', ['projectId' => 0],  false));

        $response->assertStatus(404);
    }
}
