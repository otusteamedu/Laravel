<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use Tests\Feature\Api\ApiTestCase;

class TaskTest extends ApiTestCase
{
    private Category $category;
    private Priority $priority;
    private User $executor;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::factory()->create();
        $this->priority = Priority::factory()->create();
        $this->executor = User::factory()->create();
    }

    public function test_authenticated_user_can_get_tasks_list(): void
    {
        $user = User::factory()->create();
        
        // Создаем несколько задач
        Task::factory()->count(3)->create([
            'creator_id' => $user->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'executor_id' => $this->executor->id,
        ]);

        $response = $this->authenticatedApiGet('/tasks', $user);

        $response->assertStatus(200);
        $this->assertJsonPaginationStructure($response);
        
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_unauthenticated_user_cannot_get_tasks_list(): void
    {
        $response = $this->apiGet('/tasks');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Тестовая задача',
            'description' => 'Описание тестовой задачи',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'status' => 'новая',
            'due_date' => now()->addDays(7)->format('Y-m-d')
        ];

        $response = $this->authenticatedApiPost('/tasks', $taskData, $user);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Задача успешно создана'
                 ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Тестовая задача',
            'creator_id' => $user->id,
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'status' => 'новая'
        ]);
    }

    public function test_create_task_requires_title(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'description' => 'Описание без заголовка',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ];

        $response = $this->authenticatedApiPost('/tasks', $taskData, $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_create_task_requires_valid_executor_id(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Тестовая задача',
            'executor_id' => 99999, // Несуществующий пользователь
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ];

        $response = $this->authenticatedApiPost('/tasks', $taskData, $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['executor_id']);
    }

    public function test_create_task_requires_valid_category_id(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => 99999, // Несуществующая категория
            'priority_id' => $this->priority->id,
        ];

        $response = $this->authenticatedApiPost('/tasks', $taskData, $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['category_id']);
    }

    public function test_create_task_requires_valid_priority_id(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => 99999, // Несуществующий приоритет
        ];

        $response = $this->authenticatedApiPost('/tasks', $taskData, $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['priority_id']);
    }

    public function test_create_task_validates_status(): void
    {
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'status' => 'неверный статус'
        ];

        $response = $this->authenticatedApiPost('/tasks', $taskData, $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

    public function test_authenticated_user_can_get_specific_task(): void
    {
        $user = User::factory()->create();
        
        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'executor_id' => $this->executor->id,
        ]);

        $response = $this->authenticatedApiGet("/tasks/{$task->id}", $user);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'title',
                         'description',
                         'status',
                         'createdAt',
                         'updatedAt'
                     ]
                 ]);

        $this->assertEquals($task->id, $response->json('data.id'));
    }

    public function test_get_nonexistent_task_returns_404(): void
    {
        $user = User::factory()->create();

        $response = $this->authenticatedApiGet('/tasks/99999', $user);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Задача не найдена'
                 ]);
    }

    public function test_authenticated_user_can_update_task(): void
    {
        $user = User::factory()->create();
        
        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'executor_id' => $this->executor->id,
            'title' => 'Старый заголовок'
        ]);

        $updateData = [
            'title' => 'Обновленный заголовок',
            'description' => 'Обновленное описание',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'status' => 'в работе'
        ];

        $response = $this->authenticatedApiPut("/tasks/{$task->id}", $updateData, $user);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data'
                 ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Обновленный заголовок',
            'status' => 'в работе'
        ]);
    }

    public function test_update_nonexistent_task_returns_404(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'title' => 'Обновленный заголовок',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ];

        $response = $this->authenticatedApiPut('/tasks/99999', $updateData, $user);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Задача не найдена'
                 ]);
    }

    public function test_authenticated_user_can_delete_task(): void
    {
        $user = User::factory()->create();
        
        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'executor_id' => $this->executor->id,
        ]);

        $response = $this->authenticatedApiDelete("/tasks/{$task->id}", $user);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Задача успешно удалена'
                 ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }

    public function test_delete_nonexistent_task_returns_404(): void
    {
        $user = User::factory()->create();

        $response = $this->authenticatedApiDelete('/tasks/99999', $user);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Задача не найдена'
                 ]);
    }

    public function test_unauthenticated_user_cannot_create_task(): void
    {
        $taskData = [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ];

        $response = $this->apiPost('/tasks', $taskData);

        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_update_task(): void
    {
        $task = Task::factory()->create([
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'executor_id' => $this->executor->id,
        ]);

        $updateData = [
            'title' => 'Обновленный заголовок',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ];

        $response = $this->apiPut("/tasks/{$task->id}", $updateData);

        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_delete_task(): void
    {
        $task = Task::factory()->create([
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'executor_id' => $this->executor->id,
        ]);

        $response = $this->apiDelete("/tasks/{$task->id}");

        $response->assertStatus(401);
    }
}
