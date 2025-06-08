<?php

namespace Tests\Feature\Repositories\TodoRepository;

use Tests\TestCase;
use App\Models\Todo;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\TodoComment;
use App\Models\ProjectRoleEnum;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\Todo\TodoCommentDTO;
use App\Infrastructure\Eloquent\Repositories\TodoRepository;

#[Group('repository')]
class TodoCommentsTest extends TestCase
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

    public function test_todo_comment_find(): void
    {
        $comment = TodoComment::factory([
            'author_id' => $this->user->id,
            'todo_id'   => $this->todo->id,
        ])
            ->create();

        $finded = $this->repository->findComment($comment->id, $this->todo->id);

        $this->assertNotNull($finded);
    }

    public function test_todo_comment_not_found(): void
    {
        $finded = $this->repository->findComment(0, $this->todo->id);

        $this->assertNull($finded);
    }

    public function test_todo_comment_can_added(): void
    {
        $comment = TodoComment::factory([
            'author_id' => $this->user->id,
            'todo_id'   => $this->todo->id,
        ])
            ->make();

        $payload = new TodoCommentDTO(
            todoId: $this->todo->id,
            authorId: $this->user->id,
            comment: $comment->comment,
        );

        $id = $this->repository->addComment($payload);

        $this->assertNotNull($id);
    }

    public function test_todo_comment_can_updated(): void
    {
        $comment = TodoComment::factory([
            'author_id' => $this->user->id,
            'todo_id'   => $this->todo->id,
        ])
            ->create();

        $update = TodoComment::factory([
            'author_id' => $this->user->id,
            'todo_id'   => $this->todo->id,
        ])
            ->make();

        $payload = new TodoCommentDTO(
            commentId: $comment->id,
            todoId: $comment->todo_id,
            authorId: $comment->author_id,
            comment: $update->comment,
        );

        $success = $this->repository->saveComment($payload);

        $this->assertTrue($success);

        $updated = $comment->refresh();

        $this->assertEquals($update->comment, $updated->comment, 'Не удалось обновить комментарий');
    }

    public function test_todo_comment_can_deleted(): void
    {
        $comment = TodoComment::factory([
            'author_id' => $this->user->id,
            'todo_id'   => $this->todo->id,
        ])
            ->create();

        $success = $this->repository->destroyComment($comment->id, $this->todo->id);

        $this->assertTrue($success);
    }

    public function test_todo_comments_can_fetched(): void
    {
        $count = 3;

        TodoComment::factory([
            'author_id' => $this->user->id,
            'todo_id'   => $this->todo->id,
        ])
            ->count($count)
            ->create();

        $member = User::factory()->create();

        TodoComment::factory([
            'author_id' => $member->id,
            'todo_id'   => $this->todo->id,
        ])
            ->count($count)
            ->create();

        $result = $this->repository->fetchComments($this->todo->id);

        $this->assertEquals($count * 2, count($result));
    }
}
