<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;

#[Group('http')]
class ProjectUserTest extends TestCase
{
    protected User $user;
    protected Project $project;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

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
        $response->assertSeeInOrder(['Участники проекта', 'project-user-table'], false);
    }

    public function test_user_can_invited_and_finded_in_project(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $this->project->id],  false));

        $response->assertOk();

        $formAction = route('project.users.invite', ['projectId' => $this->project->id]);
        $response->assertSee($formAction, false);
        $response->assertSeeInOrder(['Пригласить пользователя', 'name="email"'], false);

        $member = User::factory()->create();

        $payload = [
            'email'  => $member->email,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post($formAction, $payload);

        $response->assertRedirectToRoute('project.users.index', ['projectId' => $this->project->id]);
        $response->assertSessionHasNoErrors();

        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $this->project->id],  false));

        $response->assertOk();
        $response->assertSeeInOrder([$member->name, 'Пользователь', now()->translatedFormat("j F Y")], false);
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

        $response->assertRedirect(route('project.users.index', ['projectId' => $this->project->id]));
        $response->assertSessionHasErrors(['email' => 'Пользователь должен подтвердить саой email, прежде чем его можно будет приглашать для участия в проектах']);

        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $this->project->id],  false));

        $response->assertOk();
        $response->assertSee('Пользователь должен подтвердить саой email, прежде чем его можно будет приглашать для участия в проектах');
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
        $response->assertSeeInOrder([
            sprintf('id="project-list-%d"', $this->project->id),
            sprintf('id="invite-accept-btn-%d"', $this->project->id),
            sprintf('id="invite-reject-btn-%d"', $this->project->id)
        ], false);

        $response = $this
            ->actingAs($member)
            ->patch(route('project.users.join', ['projectId' => $this->project->id, 'userId' => $member->id]));


        $response->assertRedirectToRoute('projects.index');
        $response->assertSessionHasNoErrors();

        $response = $this
            ->actingAs($member)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();

        $response->assertSeeInOrder([
            sprintf('id="project-list-%d"', $this->project->id),
            sprintf('id="project-left-btn-%d"', $this->project->id)
        ]);

        $response->assertDontSee(sprintf('id="invite-accept-btn-%d"', $this->project->id));
        $response->assertDontSee(sprintf('id="invite-reject-btn-%d"', $this->project->id));
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
        $response->assertSeeInOrder([
            sprintf('id="project-list-%d"', $this->project->id),
            sprintf('id="invite-accept-btn-%d"', $this->project->id),
            sprintf('id="invite-reject-btn-%d"', $this->project->id)
        ], false);

        $response = $this
            ->actingAs($member)
            ->delete(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]));

        $response->assertRedirectToRoute('projects.index');
        $response->assertSessionHasNoErrors();

        $response = $this
            ->actingAs($member)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();
        $response->assertDontSee(sprintf('id="project-list-%d"', $this->project->id));
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
        $response->assertSeeInOrder([
            sprintf('id="project-view-%d"', $this->project->id),
            sprintf('id="project-left-btn-%d"', $this->project->id),
            route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id])
        ], false);

        $response = $this
            ->actingAs($member)
            ->delete(route('project.users.left', ['projectId' => $this->project->id, 'userId' => $member->id]));

        $response->assertRedirectToRoute('projects.index');
        $response->assertSessionHasNoErrors();

        $response = $this
            ->actingAs($member)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();
        $response->assertDontSee(sprintf('id="project-list-%d"', $this->project->id));
    }
}
