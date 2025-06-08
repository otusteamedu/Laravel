<?php

namespace Tests\Feature\Repositories\TodoRepository;

use Tests\TestCase;
use App\Models\Todo;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\Todo\TodoDTO;
use App\Infrastructure\Eloquent\Repositories\TodoRepository;
use App\Models\TodoUser;

#[Group('repository')]
class TodoTest extends TestCase
{
    protected TodoRepository $repository;
    private User $user;
    private Project $project;
    private TodoStatus $status;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new TodoRepository;

        $this->user = User::factory()->create();

        $this->project = Project::factory()
            ->has(ProjectUser::factory([
                'user_id'    => $this->user->id,
                'roles'      => [ProjectRoleEnum::ADMIN],
                'invited_at' => now(),
                'joined_at'  => now(),
                'left_at'    => null
            ]), 'projectUsers')
            ->create();

        $this->status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();
    }


    public function test_todo_find(): void
    {
        $todo = Todo::factory([
            'author_id'  => $this->user->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->create();

        $finded = $this->repository->find($todo->id, $this->project->id);

        $this->assertNotNull($finded);
    }

    public function test_todo_not_found(): void
    {
        $finded = $this->repository->find(0, $this->project->id);

        $this->assertNull($finded);
    }

    public function test_todo_can_added(): void
    {
        $todo = Todo::factory([
            'author_id'  => $this->user->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->make();

        $payload = new TodoDTO(
            title: $todo->title,
            authorId: $todo->author_id,
            projectId: $todo->project_id,
            statusId: $todo->status_id,
            description: $todo->description,
            deadline: $todo->deadline,
            options: $todo->options,
        );

        $id = $this->repository->add($payload);

        $this->assertNotNull($id);
    }

    public function test_todo_can_updated(): void
    {
        $todo = Todo::factory([
            'author_id'  => $this->user->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->create();

        $status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $update = Todo::factory([
            'author_id'  => $this->user->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->make();

        $payload = new TodoDTO(
            todoId: $todo->id,
            title: $update->title,
            authorId: $todo->author_id,
            projectId: $todo->project_id,
            statusId: $status->id,
            description: $update->description,
            deadline: $update->deadline,
            options: $update->options,
        );

        $success = $this->repository->save($payload);

        $this->assertTrue($success);

        $updated = $todo->refresh();

        $this->assertEquals($update->title, $updated->title, 'Не удалось обновить заголовок');
        $this->assertEquals($update->description, $updated->description, 'Не удалось обновить описание');
        $this->assertEquals($status->id, $updated->status_id, 'Не удалось обновить статус');
        $this->assertEquals($update->deadline, $updated->deadline, 'Не удалось обновить дедлайн');
        $this->assertEquals($update->options, $updated->options, 'Не удалось обновить дедлайн');
    }

    public function test_todo_can_deleted(): void
    {
        $todo = Todo::factory([
            'author_id'  => $this->user->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->create();

        $success = $this->repository->destroy($todo->id, $this->project->id);

        $this->assertTrue($success);
    }

    public function test_todo_can_fetched(): void
    {
        $count = 3;

        Todo::factory([
            'author_id'  => $this->user->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->has(TodoUser::factory()
                ->state(function (array $attributes, Todo $todo) {
                    return [
                        'todo_id' => $todo->id,
                        'user_id' => $todo->author_id,
                    ];
                }), 'todoUsers')
            ->count($count)
            ->create();

        $member = User::factory()->create();

        Todo::factory([
            'author_id'  => $member->id,
            'project_id' => $this->project->id,
            'status_id'  => $this->status->id,
        ])
            ->has(TodoUser::factory()
                ->state(function (array $attributes, Todo $todo) {
                    return [
                        'todo_id' => $todo->id,
                        'user_id' => $todo->author_id,
                    ];
                }), 'todoUsers')
            ->count($count)
            ->create();

        $result = $this->repository->fetch($this->project->id);
        $this->assertEquals($count * 2, count($result), 'Количество добавленных задач не соответсвует количеству выбранных');

        $result = $this->repository->fetch($this->project->id, $this->user->id);

        $this->assertEquals($count, count($result), 'Количество добавленных задач для пользователя не соответсвует количеству выбранных');
    }
}
