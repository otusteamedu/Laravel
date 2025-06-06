<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\Project;
use App\Models\TodoStatus;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\DTOs\InsertTodoStatusesDTO;
use App\Infrastructure\Eloquent\Repositories\TodoStatusRepository;

#[Group('repository')]
class TodoStatuseRepositoryTest extends TestCase
{
    protected TodoStatusRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new TodoStatusRepository;
    }

    public function test_todo_status_find(): void
    {
        $project = Project::factory()->create();

        $status = TodoStatus::factory([
            'project_id' => $project->id,
        ])
            ->create();

        $finded = $this->repository->find($status->id, $project->id);

        $this->assertNotNull($finded);
    }

    public function test_todo_status_not_found(): void
    {
        $finded = $this->repository->find(0, 0);

        $this->assertNull($finded);
    }

    public function test_todo_status_can_added(): void
    {
        $project = Project::factory()->create();

        $status = TodoStatus::factory([
            'project_id' => $project->id,
        ])
            ->make();

        $payload = new TodoStatusDTO(
            project_id: $project->id,
            name: $status->name,
            sort: $status->sort,
            color: $status->color,
        );

        $id = $this->repository->add($payload);

        $this->assertNotNull($id);
    }

    public function test_todo_status_can_updated(): void
    {
        $project = Project::factory()->create();

        $status = TodoStatus::factory([
            'project_id' => $project->id,
        ])
            ->create();

        $update = TodoStatus::factory([
            'project_id' => $project->id,
        ])
            ->make();

        $payload = new TodoStatusDTO(
            project_id: $project->id,
            name: $update->name,
            sort: $update->sort,
            color: $update->color,
            id: $status->id,
        );

        $success = $this->repository->save($payload);

        $this->assertTrue($success);

        $updated = $status->refresh();

        $this->assertEquals($update->name, $updated->name, 'Не удалось обновить имя');
        $this->assertEquals($update->sort, $updated->sort, 'Не удалоссь обрновить порядок сортировки');
        $this->assertEquals($update->color, $updated->color, 'Не удалось обновить цвет');
    }

    public function test_todo_status_can_deleted(): void
    {
        $project = Project::factory()->create();
        $status = TodoStatus::factory([
            'project_id' => $project->id,
        ])
            ->create();

        $success = $this->repository->destroy($status->id, $project->id);

        $this->assertTrue($success);
    }

    public function test_todo_status_fetch(): void
    {
        $project = Project::factory()->create();

        $statusDTOs = $this->repository->fetchForProject($project->id);

        $this->assertEquals(4, count($statusDTOs));
    }

    public function test_todo_status_can_inserted(): void
    {
        $project = Project::factory()->create();

        $statuses = TodoStatus::factory([
            'project_id' => $project->id,
        ])
            ->count(4)
            ->make();

        $statusDTOs = array_map(
            fn($status) =>
            new TodoStatusDTO(
                project_id: $project->id,
                name: $status['name'],
                sort: $status['sort'],
                color: $status['color']
            ),
            $statuses->toArray()
        );

        $this->repository->insert(new InsertTodoStatusesDTO($statusDTOs));

        $statusDTOs = $this->repository->fetchForProject($project->id);

        $this->assertEquals(8, count($statusDTOs));
    }
}
