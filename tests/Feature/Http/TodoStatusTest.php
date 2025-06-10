<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;

#[Group('http')]
class TodoStatusTest extends TestCase
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
            $user = User::factory()->create();
        }

        $project = &$this->getProject();

        if (!$project) {
            $project = Project::factory()
                ->has(ProjectUser::factory([
                    'user_id'    => $user->id,
                    'roles'      => [ProjectRoleEnum::ADMIN],
                    'invited_at' => now(),
                    'joined_at'  => now(),
                    'left_at'    => null,
                ]), 'projectUsers')
                ->create();
        }
    }

    public function test_todostatus_list_is_displayed(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $response = $this
            ->actingAs($user)
            ->get(route('project.todostatuses.index', ['projectId' => $project->id], false));

        $response->assertOk();
    }

    public function test_todostatus_can_added(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $status = TodoStatus::factory([
            'project_id' => $project->id
        ])->make();

        $payload = [
            'project_id' => $project->id,
            'name'       => $status->name,
            'sort'       => $status->sort,
            'color'      => $status->color,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('project.todostatuses.store', ['projectId' => $project->id], false), $payload);

        $response->assertSessionHasNoErrors();
    }

    public function test_todostatus_can_not_added(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        $status = TodoStatus::factory([
            'project_id' => $project->id,
            'name'       => '',
        ])->make();

        $payload = [
            'project_id' => $project->id,
            'name'       => $status->name,
            'sort'       => $status->sort,
            'color'      => $status->color,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('project.todostatuses.store', ['projectId' => $project->id], false), $payload);

        $response->assertSessionHasErrors();
    }

    public function test_todostatus_can_edited(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        /** @var TodoStatus $status */
        $status = TodoStatus::factory([
            'project_id' => $project->id
        ])->create();

        $update = TodoStatus::factory([
            'project_id' => $project->id
        ])->make();

        $payload = [
            'status_id'  => $status->id,
            'project_id' => $project->id,
            'name'       => $update->name,
            'sort'       => $update->sort,
            'color'      => $update->color,
        ];


        $response = $this
            ->actingAs($user)
            ->post(route('project.todostatuses.update', ['projectId' => $project->id], false), $payload);

        $response->assertSessionHasNoErrors();

        $updated = $status->refresh();

        $this->assertEquals($update->name, $updated->name, 'Не удалось обновить имя');
        $this->assertEquals($update->sort, $updated->sort, 'Не удалоссь обрновить порядок сортировки');
        $this->assertEquals($update->color, $updated->color, 'Не удалось обновить цвет');
    }

    public function test_todostatus_can_deleted(): void
    {
        /** @var User $user */
        $user = &$this->getUser();

        /** @var Project $project */
        $project = &$this->getProject();

        /** @var TodoStatus $status */
        $status = TodoStatus::factory([
            'project_id' => $project->id
        ])->create();

        $update = TodoStatus::factory([
            'project_id' => $project->id
        ])->make();

        $payload = [
            'status_id'  => $status->id,
            'project_id' => $project->id,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('project.todostatuses.destroy', ['projectId' => $project->id], false), $payload);

        $response->assertSessionHasNoErrors();
    }
}
