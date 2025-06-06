<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;

#[Group('project')]
class ProjectTest extends TestCase
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

        $user = &$this->getUser();

        if (!$user) {
            $user = User::query()->inRandomOrder()->first();
        }

        if (!$user) {
            $user = User::factory()->has(UserProfile::factory(), 'profile')->create();
        }
    }

    public function test_projects_list_is_displayed(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        $response = $this
            ->actingAs($user)
            ->get(route(name: 'projects.index', absolute: false));

        $response->assertOk();
    }

    public function test_project_create_screen_can_be_rendered(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        $response = $this
            ->actingAs($user)
            ->get(route(name: 'projects.create', absolute: false));

        $response->assertOk();
    }

    public function test_new_project_create_validation_error(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        $payload = [
            'name'        => '1',
            'description' => 1,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route(name: 'projects.store', absolute: false), $payload);

        $response->assertSessionHasErrors();
    }

    public function test_new_project_can_created(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        $ftyProject = Project::factory()->make();

        $payload = [
            'name'        => $ftyProject->name,
            'description' => $ftyProject->description,
            'user_id'     => $user->id,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route(name: 'projects.store', absolute: false), $payload);

        $response->assertStatus(302);

        $targetUrl = parse_url($response->getTargetUrl(), PHP_URL_PATH);

        preg_match('/^\/projects\/(\d+)$/', $targetUrl, $matches);

        $projectId = $matches[1] ?? null;

        $response->assertRedirectToRoute('projects.show', ['projectId' => $projectId]);

        $project = &$this->getProject();

        $project = Project::query()
            ->where('id', $projectId)
            ->first();

        $this->assertNotNull($project, 'Проект не найден');
    }

    public function test_new_project_can_show(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertOk();
    }

    public function test_project_user_admin_is_assigned(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $projectUser = ProjectUser::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->whereJsonContains('roles', ProjectRoleEnum::ADMIN->value)
            ->whereNotNull('joined_at')
            ->whereNull('left_at')
            ->first();

        $this->assertNotNull($projectUser, 'Администратор нового проекта не назначен');
    }

    public function test_project_default_todo_statuses_is_creaated(): void
    {
        /** @var Project $project */
        $project = &$this->getProject();

        $todoStatuses = TodoStatus::query()
            ->where('project_id', $project->id)
            ->pluck('name')
            ->toArray();

        $this->assertContains('Новая', $todoStatuses, 'Статус задачи "Новая" не добавлен в проект');
        $this->assertContains('В работе', $todoStatuses, 'Статус задачи "В работе" не добавлен в проект');
        $this->assertContains('Завершена', $todoStatuses, 'Статус задачи "Завершена" не добавлен в проект');
        $this->assertContains('Архив', $todoStatuses, 'Статус задачи "Архив" не добавлен в проект');
    }

    public function test_project_page_is_displayed(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('projects.show', ['projectId' => $project->id], false));

        $response->assertOk();
    }

    public function test_project_edit_screen_can_be_rendered(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('projects.edit', ['projectId' => $project->id], false));

        $response->assertOk();
    }

    public function test_project_update_validation_error(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $payload = [
            'name'        => '1',
            'description' => 1,
        ];

        $response = $this
            ->actingAs($user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $response->assertSessionHasErrors();
    }

    public function test_project_can_updated(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();


        $ftyProject = Project::factory()->make();

        $payload = [
            'name'        => $ftyProject->name,
            'description' => $ftyProject->description,
        ];

        $response = $this
            ->actingAs($user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $project = Project::query()
            ->where('id', $project->id)
            ->first();

        $this->assertEquals($ftyProject->name, $project->name, 'Имя проекта не обновилось');
        $this->assertEquals($ftyProject->description, $project->description, 'Описание проекта не обновилось');
    }

    public function test_project_can_delete(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->delete(route('projects.destroy', ['projectId' => $project->id],  false));


        $check = Project::query()
            ->where('id', $project->id)
            ->first();

        $this->assertNull($check, 'Проект не удален');
    }

    public function test_project_view_access_denied(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('projects.show', ['projectId' => $project->id],  false));

        $response->assertStatus(403);
    }

    public function test_project_edit_access_denied(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('projects.edit', ['projectId' => $project->id],  false));

        $response->assertStatus(403);
    }

    public function test_project_update_access_denied(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();


        $ftyProject = Project::factory()->make();

        $payload = [
            'name'        => $ftyProject->name,
            'description' => $ftyProject->description,
        ];

        $response = $this
            ->actingAs($user)
            ->put(route('projects.update', ['projectId' => $project->id],  false), $payload);

        $response->assertStatus(403);
    }
    public function test_project_delete_access_denied(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->delete(route('projects.destroy', ['projectId' => $project->id],  false));

        $response->assertStatus(403);
    }

    public function test_project_users_is_deleted(): void
    {
        /** @var Project $project */
        $project = &$this->getProject();

        $check = ProjectUser::query()
            ->where('project_id', $project->id)
            ->whereNull('left_at')
            ->count();

        $this->assertEquals(0, $check, 'Пользователи не удалены из проекта');
    }
}
