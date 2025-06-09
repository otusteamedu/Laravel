<?php

namespace Tests\Feature\Http;

use App\Services\UseCases\Commands\ProjectUser\Invite\Command;
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
        $response->assertSeeInOrder(['<h4 class="mb-4">Участники проекта</h4>', '<table class="project-user-table table table-hover">'], false);
    }

    public function test_user_can_invited_and_finded_in_project(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $this->project->id],  false));

        $response->assertOk();

        $formAction = route('project.users.invite', ['projectId' => $this->project->id]);
        $response->assertSee($formAction, false);
        $response->assertSeeInOrder(['<h4 class="mb-4">Пригласить пользователя</h4>', '<input name="email"'], false);

        $member = User::factory()->create();

        $payload = [
            'email'  => $member->email,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post($formAction, $payload);

        $response->assertRedirectToRoute('project.users.index', ['projectId' => $this->project->id]);
        $response->assertSessionHasNoErrors();

        $finded = $this->repository->findUser($this->project->id, $member->id);
        $this->assertNotNull($finded);
    }

    public function test_user_invite_to_project_with_unverified_mail_failed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $this->project->id],  false));

        $response->assertOk();

        $member = User::factory(['email_verified_at' => null])->create();

        $payload = [
            'email'  => $member->email,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('project.users.invite', ['projectId' => $this->project->id],  false), $payload);

        $response->assertRedirectBack();
        $response->assertSessionHasErrors(['email' => 'Пользователь должен подтвердить саой email, прежде чем его можно будет приглашать для участия в проектах']);

        $finded = $this->repository->findUser($this->project->id, $member->id);
        $this->assertNull($finded);
    }

    public function test_user_can_accept_invite(): void
    {
        /** @var User $member */
        $member = User::factory()->create();

        ProjectUser::factory([
            'project_id' => $this->project->id,
            'user_id'    => $member->id,
            'roles'      => [ProjectRoleEnum::MEMBER],
            'invited_at' => now(),
            'joined_at'  => null,
            'left_at'    => null,
        ])
            ->create();

        $response = $this
            ->actingAs($member)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();

        $response->assertSee(route('project.users.join', ['projectId' => $this->project->id, 'userId' => $member->id]), false);
        $response->assertSee(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]), false);

        $response = $this
            ->actingAs($member)
            ->patch(route('project.users.join', ['projectId' => $this->project->id, 'userId' => $member->id]));

        $response->assertRedirectBack();
        $response->assertSessionHasNoErrors();

        $success = $this->repository->userHasRole($this->project->id, $member->id, [ProjectRoleEnum::MEMBER]);
        $this->assertTrue($success);
    }

    public function test_user_can_reject_invite(): void
    {
        /** @var User $member */
        $member = User::factory()->create();

        ProjectUser::factory([
            'project_id' => $this->project->id,
            'user_id'    => $member->id,
            'roles'      => [ProjectRoleEnum::MEMBER],
            'invited_at' => now(),
            'joined_at'  => null,
            'left_at'    => null,
        ])
            ->create();

        $response = $this
            ->actingAs($member)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();
        $response->assertSee(route('project.users.join', ['projectId' => $this->project->id, 'userId' => $member->id]), false);
        $response->assertSee(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]), false);

        $response = $this
            ->actingAs($member)
            ->delete(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]));

        $response->assertRedirectToRoute('projects.index');
        $response->assertSessionHasNoErrors();

        $finded = $this->repository->findUser($this->project->id, $member->id);
        $this->assertNull($finded);
    }

    public function test_user_can_left_project(): void
    {
        /** @var User $member */
        $member = User::factory()->create();

        ProjectUser::factory([
            'project_id' => $this->project->id,
            'user_id'    => $member->id,
            'roles'      => [ProjectRoleEnum::MEMBER],
            'invited_at' => now(),
            'joined_at'  => now(),
            'left_at'    => null,
        ])
            ->create();

        $response = $this
            ->actingAs($member)
            ->get(route('projects.show', ['projectId' => $this->project->id], false));

        $response->assertOk();
        $response->assertSee(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]), false);

        $response = $this
            ->actingAs($member)
            ->delete(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]));

        $response->assertRedirectToRoute('projects.index');
        $response->assertSessionHasNoErrors();

        $finded = $this->repository->findUser($this->project->id, $member->id);
        $this->assertNull($finded);
    }
}
