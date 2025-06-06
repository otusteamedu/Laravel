<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Todo;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoUser;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\TodoComment;
use App\Models\TodoRoleEnum;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;

#[Group('todo')]
class TodoTest extends TestCase
{
    private User $user;
    private Project $project;
    private TodoStatus $status;

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
                'left_at'    => null
            ]), 'projectUsers')
            ->create();

        $this->status = TodoStatus::factory([
            'project_id' => $this->project->id
        ])->create();

        $member = User::factory()->create();

        ProjectUser::factory([
            'project_id' => $this->project->id,
            'user_id'    => $member->id,
            'roles'      => [ProjectRoleEnum::MEMBER],
            'invited_at' => now(),
            'joined_at'  => now(),
            'left_at'    => null
        ])
            ->create();

        Todo::factory([
            'author_id'   => $member->id,
            'project_id'  => $this->project->id,
            'status_id'   => $this->status->id,
        ])
            ->create();
    }

    public function test_todo_create(): void
    {
        $todo = Todo::factory([
            'author_id'   => $this->user->id,
            'project_id'  => $this->project->id,
            'status_id'   => $this->status->id,
        ])
            ->create();

        $this->assertNotNull($todo);
    }

    public function test_todo_scope_member(): void
    {
        $member = Todo::query()
            ->member($this->user)
            ->where('project_id', $this->project->id)
            ->get();

        $this->assertNotNull($member);
    }

    public function test_todo_scope_not_member(): void
    {
        $notMember = Todo::query()
            ->notMember($this->user)
            ->where('project_id', $this->project->id)
            ->get();

        $this->assertNotNull($notMember);
    }

    public function test_todo_commets_relation(): void
    {
        $comments = 3;

        $todo = Todo::factory([
            'author_id'   => $this->user->id,
            'project_id'  => $this->project->id,
            'status_id'   => $this->status->id,
        ])
            ->create();

        TodoComment::factory([
            'todo_id' => $todo->id,
            'user_id' => $todo->author_id,
        ])
            ->count($comments)
            ->create();

        $todo->load('comments');

        $this->assertEquals($comments, $todo->comments->count());
    }
}
