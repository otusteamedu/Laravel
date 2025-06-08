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
class ProjectTest extends TestCase
{
    protected ProjectRepository $repository;
    protected User $user;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new ProjectRepository;

        $this->user = User::factory()->has(UserProfile::factory(), 'profile')->create();
    }

    public function test_projects_list_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();
    }

    public function test_project_create_screen_can_be_rendered(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route(name: 'projects.create', absolute: false));

        $response->assertOk();
    }

    public function test_new_project_create_validation_error(): void
    {
        $payload = [
            'name'        => '1',
            'description' => 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route(name: 'projects.store', absolute: false), $payload);

        $response->assertSessionHasErrors();
    }

    public function test_new_project_can_created(): void
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

        $this->assertNotNull($projectId, 'TargetURL не соответсвует ожиданиям');
        $response->assertRedirectToRoute('projects.show', ['projectId' => $projectId]);

        $project = $this->repository->find($projectId);

        $this->assertNotNull($project, 'Проект не найден');
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
    }

    public function test_project_user_admin_is_assigned(): void
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

        $success = $this->repository->userHasRole($project->id, $this->user->id, [ProjectRoleEnum::ADMIN]);

        $this->assertTrue($success, 'Администратор нового проекта не назначен');
    }

    public function test_project_default_todo_statuses_is_creaated(): void
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

        $statuses = $this->repository->fetchTodoStatuses($project->id);

        $todoStatuses = array_map(fn($status) => $status->name, $statuses);

        $this->assertContains('Новая', $todoStatuses, 'Статус задачи "Новая" не добавлен в проект');
        $this->assertContains('В работе', $todoStatuses, 'Статус задачи "В работе" не добавлен в проект');
        $this->assertContains('Завершена', $todoStatuses, 'Статус задачи "Завершена" не добавлен в проект');
        $this->assertContains('Архив', $todoStatuses, 'Статус задачи "Архив" не добавлен в проект');
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

        $payload = [
            'name'        => '1',
            'description' => 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $response->assertSessionHasErrors();
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

        $response->assertSessionHasNoErrors();

        $updated = $this->repository->find($project->id);

        $this->assertEquals($update->name, $updated->name, 'Имя проекта не обновилось');
        $this->assertEquals($update->description, $updated->description, 'Описание проекта не обновилось');
    }

    public function test_project_can_delete(): void
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

        $response->assertSessionHasNoErrors();

        $check = $this->repository->find($project->id);

        $this->assertNull($check, 'Проект не удален');
    }

    public function test_project_view_access_denied(): void
    {
        $project = Project::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertStatus(403);
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
