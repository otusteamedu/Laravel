<?php

namespace Tests\Feature\Repositories\ProjectRepository;

use Tests\TestCase;
use App\Models\Project;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Infrastructure\Eloquent\Repositories\ProjectRepository;

#[Group('repository')]
class ProjectTest extends TestCase
{
    protected ProjectRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new ProjectRepository;
    }

    public function test_project_find(): void
    {
        $project = Project::factory()->create();

        $finded = $this->repository->find($project->id);

        $this->assertNotNull($finded);
    }

    public function test_project_not_found(): void
    {
        $finded = $this->repository->find(0);

        $this->assertNull($finded);
    }

    public function test_project_can_added(): void
    {
        $project = Project::factory()->make();

        $payload = new ProjectDTO(
            name: $project->name,
            description: $project->description,
        );

        $id = $this->repository->add($payload);

        $this->assertNotNull($id);
    }

    public function test_project_can_updated(): void
    {
        $project = Project::factory()->create();

        $update = Project::factory()->make();

        $payload = new ProjectDTO(
            name: $update->name,
            description: $update->description,
            projectId: $project->id
        );

        $success = $this->repository->save($payload);

        $this->assertTrue($success);

        $updated = $project->refresh();

        $this->assertEquals($update->name, $updated->name, 'Не удалось обновить имя');
        $this->assertEquals($update->description, $updated->description, 'Не удалось обновить описание');
    }

    public function test_project_can_deleted(): void
    {
        $project = Project::factory()->create();

        $success = $this->repository->destroy($project->id);

        $this->assertTrue($success);
    }
}
