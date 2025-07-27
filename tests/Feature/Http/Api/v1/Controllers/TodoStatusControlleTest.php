<?php

namespace Tests\Feature\Http\Api\V1\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Testing\Fluent\AssertableJson;

#[Group('apit')]
class TodoStatusControlleTest extends TestCase
{
    protected User $user;
    protected Project $project;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->user = User::factory()->create();

        $this->project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null,
            ]), 'projectUsers')
            ->create();
    }

    public function testApiTodostatusIndexSuccess(): void
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', route('api.todo-status.index', ['projectId' => $this->project->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'payload' => [
                    '*' => [
                        'projectId',
                        'name',
                        'sort',
                        'color',
                        'statusId'
                    ]
                ],
                'success',
                'code'
            ]);
    }

    public function testApiTodostatusShowSuccess(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', route('api.todo-status.show', ['projectId' => $this->project->id, 'statusId' => $status->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'payload' => [
                    'projectId',
                    'name',
                    'sort',
                    'color',
                    'statusId'
                ],
                'success',
                'code'
            ]);
    }

    public function testApiTodostatusShowFailed(): void
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', route('api.todo-status.show', ['projectId' => $this->project->id, 'statusId' => 0]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'status_id',
                ],
                'code',
            ])
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('message', 'Ошибка валидации данных запроса')
                        ->where('errors.status_id.0', 'Статус не существует')
                        ->where('code', 400);
                }
            );
    }

    public function testApiTodostatusAddSuccess(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->make();

        $payload = [
            'project_id' => $this->project->id,
            'name'       => $status->name,
            'sort'       => $status->sort,
            'color'      => $status->color,
        ];

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', route('api.todo-status.store', ['projectId' => $this->project->id]), $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'payload' => [
                    'id',
                ],
                'success',
                'code',
            ]);

        $this->assertDatabaseHas(TodoStatus::class, $payload);
    }

    public function testApiTodostatusAddFailed(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id,
            'name'       => '',

        ])->make();

        $payload = [
            'project_id' => $this->project->id,
            'name'       => $status->name,
            'sort'       => $status->sort,
            'color'      => $status->color,
        ];

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', route('api.todo-status.store', ['projectId' => $this->project->id]), $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
                'code',
            ])
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('message', 'Ошибка валидации данных запроса')
                        ->where('errors.name.0', 'Пожалуйста, укажите название статуса.')
                        ->where('code', 400);
                }
            );
    }

    public function testApiTodostatusEditSuccess(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $update = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->make();

        $payload = [
            'status_id'  => $status->id,
            'project_id' => $this->project->id,
            'name'       => $update->name,
            'sort'       => $update->sort,
            'color'      => $update->color,
        ];

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('PATCH', route('api.todo-status.update', ['projectId' => $this->project->id, 'statusId' => $status->id]), $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'payload',
                'success',
                'code',
            ])
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('success', true)
                        ->where('code', 200)
                        ->etc();
                }
            );

        $dbData = [
            'id' => $status->id,
            'project_id' => $this->project->id,
            'name'       => $update->name,
            'sort'       => $update->sort,
            'color'      => $update->color,
        ];

        $this->assertDatabaseHas(TodoStatus::class, $dbData);
    }

    public function testApiTodostatusEditFailed(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $payload = [
            'status_id'  => $status->id,
            'project_id' => $this->project->id,
            'name'       => '',
            'sort'       => '',
            'color'      => '',
        ];

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('PATCH', route('api.todo-status.update', ['projectId' => $this->project->id, 'statusId' => $status->id]), $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'sort',
                    'color',
                ],
                'code',
            ])
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('message', 'Ошибка валидации данных запроса')
                        ->where('errors.name.0', 'Пожалуйста, укажите название статуса.')
                        ->where('errors.sort.0', 'Пожалуйста, укажите порядок сортировки статуса.')
                        ->where('errors.color.0', 'Пожалуйста, укажите цвет для выделения статуса.')
                        ->where('code', 400);
                }
            );
    }

    public function testApiTodostatusDeleteSuccess(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', route('api.todo-status.destroy', ['projectId' => $this->project->id, 'statusId' => $status->id]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'payload',
                'success',
                'code',
            ])
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('success', true)
                        ->where('code', 200)
                        ->etc();
                }
            );

        $this->assertSoftDeleted(TodoStatus::class, ['id' => $status->id]);
    }

    public function testApiTodostatusDeleteFailed(): void
    {
        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', route('api.todo-status.destroy', ['projectId' => $this->project->id, 'statusId' => 0]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'status_id',
                ],
                'code',
            ])
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('message', 'Ошибка валидации данных запроса')
                        ->where('errors.status_id.0', 'Статус не существует')
                        ->where('code', 400);
                }
            );

        $this->assertNotSoftDeleted(TodoStatus::class, ['id' => $status->id]);
    }
}
