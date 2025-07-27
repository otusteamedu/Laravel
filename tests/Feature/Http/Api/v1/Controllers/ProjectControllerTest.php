<?php

namespace Tests\Feature\Http\Api\V1\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\TodoStatus;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Testing\Fluent\AssertableJson;

#[Group('api')]
class ProjectControllerTest extends TestCase
{
    public function testApiCreateProjectValidationError(): void
    {
        /** @var  User $user */
        $user = User::factory()->create(['password' => 'password']);

        $payload = [
            'name'        => '1',
            'description' => 1,
            'user_id'     => $user->id,
        ];

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/v1/projects/store', $payload);

        $response
            ->assertStatus(422)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->where('success', false)
                        ->where('message', 'Validation errors')
                        ->where('errors.name.0', 'Название проекта слишком короткое.')
                        ->where('errors.description.0', 'Описание проекта должно быть строкой.');
                }
            );
    }

    public function testApiCreateProjectSuccess(): void
    {
        /** @var  User $user */
        $user = User::factory()->create(['password' => 'password']);

        $project = Project::factory()->make();

        $payload = [
            'name'        => $project->name,
            'description' => $project->description,
            'user_id'     => $user->id,
        ];

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/v1/projects/store', $payload);

        $response
            ->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) {
                    $json->has('payload.id')
                        ->where('success', true)
                        ->where('code', 200);
                }
            );

        $projectId = $response->json()['payload']['id'];

        $this->assertDatabaseHas(ProjectUser::class, ['project_id' => $projectId, 'user_id' => $user->id, 'roles' => "[\"Администратор\"]"]);
        $this->assertDatabaseHas(TodoStatus::class, ['project_id' => $projectId, 'name' => 'Новая']);
        $this->assertDatabaseHas(TodoStatus::class, ['project_id' => $projectId, 'name' => 'В работе']);
        $this->assertDatabaseHas(TodoStatus::class, ['project_id' => $projectId, 'name' => 'Завершена']);
        $this->assertDatabaseHas(TodoStatus::class, ['project_id' => $projectId, 'name' => 'Архив']);
    }
}
