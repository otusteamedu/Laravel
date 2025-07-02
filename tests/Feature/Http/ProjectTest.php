<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;

#[Group('http')]
class ProjectTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->user = User::factory()->has(UserProfile::factory(), 'profile')->create();
    }

    public function test_projects_list_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();
        $response->assertSee(route('projects.create'), false);
    }

    public function test_project_can_show(): void
    {
        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertOk();
        $response->assertSeeInOrder(['Информация', sprintf('id="project-view-%d"', $project->id)], false);
    }

    public function test_project_create_form_can_be_rendered(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.create', absolute: false));

        $response->assertOk();

        $response->assertSee('name="name"', false);
        $response->assertSee('name="description"', false);
        $response->assertSee(route('projects.store'), false);
    }

    public function test_project_create_validation_error(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.create', absolute: false));

        $response->assertOk();

        $payload = [
            'name'        => '1',
            'description' => 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route(name: 'projects.store', absolute: false), $payload);

        $response->assertRedirectToRoute('projects.create');
        $response->assertSessionHasErrors(['name' => 'Название проекта слишком короткое.']);
        $response->assertSessionHasErrors(['description' => 'Описание проекта должно быть строкой.']);

        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.create', absolute: false));

        $response->assertOk();
        $response->assertSee('Название проекта слишком короткое.', false);
        $response->assertSee('Описание проекта должно быть строкой.', false);
    }

    public function test_project_can_created_succesfully(): void
    {
        $project = Project::factory()->make();

        $payload = [
            'name'        => $project->name,
            'description' => $project->description,
            'user_id'     => $this->user->id,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route(name: 'projects.store', absolute: false), $payload);

        $targetUrl = $response->getTargetUrl();

        $routeMask = route('projects.show', ['projectId' => '__placeholder']);

        $diff = array_diff(
            str_split($targetUrl),
            str_split($routeMask)
        );

        $projectId = implode($diff);

        $this->assertNotNull($projectId, 'TargetURL не соответствует ожиданиям.');

        $response->assertRedirectToRoute('projects.show', ['projectId' => $projectId]);

        $response = $this
            ->actingAs($this->user)
            ->get($targetUrl);

        $response->assertOk();
        $response->assertSeeInOrder(['Информация', sprintf('id="project-view-%d"', $projectId)], false);
        $response->assertSee(route('project.users.index', ['projectId' => $projectId]));

        $response = $this
            ->actingAs($this->user)
            ->get(route('project.users.index', ['projectId' => $projectId], false));

        $response->assertOk();
        $response->assertSeeInOrder([sprintf('id="project-member-list-%d"', $this->user->id), $this->user->name, 'Администратор'], false);
        $response->assertSee(route('project.todostatuses.index', ['projectId' => $projectId]));

        $response = $this
            ->actingAs($this->user)
            ->get(route('project.todostatuses.index', ['projectId' => $projectId], false));

        $response->assertOk();
        $response->assertSeeInOrder(['Добавить статус', 'Новая', 'В работе', 'Завершена', 'Архив'], false);
    }

    public function test_project_update_validation_error(): void
    {
        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertOk();
        $response->assertSee('Редактирование проекта', false);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="description"', false);
        $response->assertSeeInOrder([
            sprintf('id="project-edit-form-%d"', $project->id),
            route('projects.update', ['projectId' => $project->id], false)
        ]);

        $payload = [
            'name'        => '1',
            'description' => 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $response->assertRedirectToRoute('projects.show', ['projectId' => $project->id]);
        $response->assertSessionHasErrors(['name' => 'Название проекта слишком короткое.']);
        $response->assertSessionHasErrors(['description' => 'Описание проекта должно быть строкой.']);


        $response = $this
            ->actingAs($this->user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertOk();
        $response->assertSee('Название проекта слишком короткое.', false);
        $response->assertSee('Описание проекта должно быть строкой.', false);
    }

    public function test_project_can_updated(): void
    {
        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();

        $update = Project::factory()->make();

        $payload = [
            'name'        => $update->name,
            'description' => $update->description,
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $response->assertRedirectToRoute('projects.show', ['projectId' => $project->id]);
        $response->assertSessionHasNoErrors();

        $response = $this
            ->actingAs($this->user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertOk();
        $response->assertSee($update->name, false);
        $response->assertSee($update->description, false);
    }

    public function test_project_can_deleted_succesfully(): void
    {
        $project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('projects.destroy', ['projectId' => $project->id],  false));


        $response->assertRedirectToRoute('projects.index');
        $response->assertSessionHasNoErrors();

        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.index', parameters: false));

        $response->assertOk();
        $response->assertDontSee(sprintf('id="project-list-%d"', $project->id));
    }

    public function test_project_view_access_denied_code_404_instead_403(): void
    {
        $project = Project::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertStatus(404);
    }

    public function test_project_update_access_denied(): void
    {
        $project = Project::factory()->create();

        $update = Project::factory()->make();

        $payload = [
            'name'        => $update->name,
            'description' => $update->description,
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $response->assertStatus(403);
    }
    public function test_project_delete_access_denied(): void
    {
        $project = Project::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('projects.destroy', ['projectId' => $project->id],  false));

        $response->assertStatus(403);
    }
}
