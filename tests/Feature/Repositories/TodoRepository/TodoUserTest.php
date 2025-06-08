<?php

namespace Tests\Feature\Repositories\TodoRepository;

use Tests\TestCase;
use App\Models\Todo;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoUser;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\TodoRoleEnum;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use App\Infrastructure\Eloquent\Repositories\TodoRepository;

#[Group('repository')]
class TodoUserTest extends TestCase
{
    protected TodoRepository $repository;
    private User $user;
    private Project $project;
    private TodoStatus $status;
    private Todo $todo;

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

        $this->todo = Todo::factory([
            'author_id'   => $this->user->id,
            'project_id'  => $this->project->id,
            'status_id'   => $this->status->id,
        ])
            ->create();
    }

    public function test_todo_user_has_role(): void
    {
        TodoUser::factory([
            'todo_id' => $this->todo->id,
            'user_id' => $this->user->id,
            'role'    => TodoRoleEnum::RESPONSIBLE,
        ])
            ->create();

        $success = $this->repository->userHasRole($this->todo->id, $this->user->id, TodoRoleEnum::RESPONSIBLE);

        $this->assertTrue($success);
    }

    public function test_todo_user_habe_not_role(): void
    {
        TodoUser::factory([
            'todo_id' => $this->todo->id,
            'user_id' => $this->user->id,
            'role'    => TodoRoleEnum::RESPONSIBLE,
        ])
            ->create();

        $success = $this->repository->userHasRole($this->todo->id, $this->user->id, TodoRoleEnum::PERFORMER);

        $this->assertFalse($success);
    }

    public function test_todo_user_found(): void
    {
        TodoUser::factory([
            'todo_id' => $this->todo->id,
            'user_id' => $this->user->id,
            'role'    => TodoRoleEnum::RESPONSIBLE,
        ])
            ->create();

        $finded = $this->repository->findUser($this->todo->id, $this->user->id);

        $this->assertNotNull($finded);
    }

    public function test_todo_user_not_found(): void
    {
        TodoUser::factory([
            'todo_id' => $this->todo->id,
            'user_id' => $this->user->id,
            'role'    => TodoRoleEnum::RESPONSIBLE,
        ])
            ->create();

        $member = User::factory()->create();

        $finded = $this->repository->findUser($this->todo->id, $member->id);

        $this->assertNull($finded);
    }

    public function test_todo_user_can_added(): void
    {
        $success = $this->repository->saveUser($this->todo->id, $this->user->id, TodoRoleEnum::RESPONSIBLE);

        $this->assertTrue($success, 'Ошибка добавления пользователя');

        $finded = $this->repository->findUser($this->todo->id, $this->user->id);

        $this->assertNotNull($finded, 'Пользователь небыл добавлен');
    }

    public function test_todo_user_can_removed(): void
    {
        TodoUser::factory([
            'todo_id' => $this->todo->id,
            'user_id' => $this->user->id,
            'role'    => TodoRoleEnum::RESPONSIBLE,
        ])
            ->create();

        $success = $this->repository->renoveUser($this->todo->id, $this->user->id);

        $this->assertTrue($success, 'Ошибка удаления пользователя');

        $finded = $this->repository->findUser($this->todo->id, $this->user->id);

        $this->assertNull($finded, 'Пользователь небыл удален');
    }

    public function test_todo_can_change_user_role(): void
    {
        TodoUser::factory([
            'todo_id' => $this->todo->id,
            'user_id' => $this->user->id,
            'role'    => TodoRoleEnum::RESPONSIBLE,
        ])
            ->create();

        $success = $this->repository->saveUser($this->todo->id, $this->user->id, TodoRoleEnum::WATCHER);

        $this->assertTrue($success, 'Не удалось обновить роль пользователя');

        $success = $this->repository->userHasRole($this->todo->id, $this->user->id, TodoRoleEnum::WATCHER);

        $this->assertTrue($success, 'Роль пользователя не изменилась.');
    }

    public function test_todo_user_can_fetched(): void
    {
        $count = 3;

        $users = User::factory()->count($count)->create();

        foreach ($users as $user) {
            TodoUser::factory([
                'todo_id' => $this->todo->id,
                'user_id' => $user->id,
            ])
                ->create();
        }

        $result = $this->repository->fetchUsers($this->todo->id);

        $this->assertEquals($count, count($result));
    }
}
