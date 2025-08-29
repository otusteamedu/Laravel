<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Category;
use App\Models\Priority;
use Tests\Feature\Api\ApiTestCase;

class ValidationTest extends ApiTestCase
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

    public function test_login_validation_rules(): void
    {
        // Пустые данные
        $response = $this->apiPost('/auth/login', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);

        // Неверный формат email
        $response = $this->apiPost('/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);

        // Слишком короткий пароль
        $response = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => '123'
        ]);

        $response->assertStatus(401);
    }

    public function test_create_task_validation_rules(): void
    {
        $user = User::factory()->create();

        // Пустые обязательные поля
        $response = $this->authenticatedApiPost('/tasks', [], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'title',
                     'executor_id',
                     'category_id',
                     'priority_id'
                 ]);

        // Слишком длинный заголовок
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => str_repeat('a', 256), // Превышаем лимит
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);

        // Несуществующий executor_id
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => 99999,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['executor_id']);

        // Несуществующий category_id
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => 99999,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['category_id']);

        // Несуществующий priority_id
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => 99999,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['priority_id']);

        // Неверный статус
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'status' => 'неверный_статус'
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);

        // Неверная дата
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'due_date' => 'не-дата'
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['due_date']);

        // Дата в прошлом
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
            'due_date' => '2020-01-01'
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['due_date']);
    }

    public function test_update_task_validation_rules(): void
    {
        $user = User::factory()->create();

        // Обновление с валидными необязательными полями должно работать
        $response = $this->authenticatedApiPut('/tasks/99999', [
            'title' => 'Обновленный заголовок'
        ], $user);

        // Ожидаем 404 для несуществующей задачи, но не ошибки валидации
        $response->assertStatus(404);

        // Слишком длинный заголовок
        $response = $this->authenticatedApiPut('/tasks/99999', [
            'title' => str_repeat('a', 256)
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);

        // Несуществующий executor_id
        $response = $this->authenticatedApiPut('/tasks/99999', [
            'executor_id' => 99999
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['executor_id']);

        // Неверный статус
        $response = $this->authenticatedApiPut('/tasks/99999', [
            'status' => 'неверный_статус'
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

    public function test_valid_status_values_are_accepted(): void
    {
        $user = User::factory()->create();

        $validStatuses = ['новая', 'в работе', 'выполнена', 'отменена'];

        foreach ($validStatuses as $status) {
            $response = $this->authenticatedApiPost('/tasks', [
                'title' => 'Тестовая задача',
                'executor_id' => $this->executor->id,
                'category_id' => $this->category->id,
                'priority_id' => $this->priority->id,
                'status' => $status
            ], $user);

            // Ожидаем успешное создание для всех валидных статусов
            $response->assertStatus(201);
        }
    }

    public function test_title_is_required_but_description_is_optional(): void
    {
        $user = User::factory()->create();

        // Без описания должно работать
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Задача без описания',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(201);

        // Без заголовка не должно работать
        $response = $this->authenticatedApiPost('/tasks', [
            'description' => 'Описание без заголовка',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_numeric_fields_validation(): void
    {
        $user = User::factory()->create();

        // Строки вместо чисел
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => 2,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['executor_id']);

        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => 12,
            'priority_id' => $this->priority->id,
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['category_id']);

        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Тестовая задача',
            'executor_id' => $this->executor->id,
            'category_id' => $this->category->id,
            'priority_id' => 'не-число',
        ], $user);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['priority_id']);
    }
}
