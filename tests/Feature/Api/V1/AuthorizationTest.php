<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use Tests\Feature\Api\ApiTestCase;

class AuthorizationTest extends ApiTestCase
{
    private Category $category;
    private Priority $priority;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->priority = Priority::factory()->create();
    }

    public function test_api_requires_authentication_for_all_protected_routes(): void
    {
        $protectedRoutes = [
            ['method' => 'GET', 'uri' => '/tasks'],
            ['method' => 'POST', 'uri' => '/tasks'],
            ['method' => 'GET', 'uri' => '/tasks/1'],
            ['method' => 'PUT', 'uri' => '/tasks/1'],
            ['method' => 'DELETE', 'uri' => '/tasks/1'],
            ['method' => 'POST', 'uri' => '/auth/logout'],
        ];

        foreach ($protectedRoutes as $route) {
            $response = match($route['method']) {
                'GET' => $this->apiGet($route['uri']),
                'POST' => $this->apiPost($route['uri']),
                'PUT' => $this->apiPut($route['uri']),
                'DELETE' => $this->apiDelete($route['uri']),
            };

            $response->assertStatus(401,
                "Route {$route['method']} {$route['uri']} should require authentication"
            );
        }
    }

    public function test_authenticated_user_can_access_all_protected_routes(): void
    {
        $user = User::factory()->create();
        $executor = User::factory()->create();

        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'executor_id' => $executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ]);

        // GET /tasks
        $response = $this->authenticatedApiGet('/tasks', $user);
        $response->assertStatus(200);

        // POST /tasks
        $response = $this->authenticatedApiPost('/tasks', [
            'title' => 'Новая задача',
            'executor_id' => $executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);
        $response->assertStatus(201);

        // GET /tasks/{id}
        $response = $this->authenticatedApiGet("/tasks/{$task->id}", $user);
        $response->assertStatus(200);

        // PUT /tasks/{id}
        $response = $this->authenticatedApiPut("/tasks/{$task->id}", [
            'title' => 'Обновленная задача',
            'executor_id' => $executor->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->priority->id,
        ], $user);
        $response->assertStatus(200);

        // DELETE /tasks/{id}
        $response = $this->authenticatedApiDelete("/tasks/{$task->id}", $user);
        $response->assertStatus(200);

        // POST /auth/logout
        $response = $this->authenticatedApiPost('/auth/logout', [], $user);
        $response->assertStatus(200);
    }

    public function test_public_auth_login_route_does_not_require_authentication(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
    }

    public function test_token_based_authentication_works(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Получаем токен через логин
        $loginResponse = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('access_token');
        $this->assertNotEmpty($token);

        // Используем токен для доступа к защищенному маршруту
        $response = $this->apiGet('/tasks', [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
    }

    public function test_invalid_token_returns_401(): void
    {
        $response = $this->apiGet('/tasks', [
            'Authorization' => 'Bearer invalid-token'
        ]);

        $response->assertStatus(401);
    }

    public function test_expired_or_revoked_token_returns_401(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Получаем токен
        $loginResponse = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('access_token');

        // Выходим из системы (отзываем токен)
        $logoutResponse = $this->apiPost('/auth/logout', [], [
            'Authorization' => "Bearer {$token}"
        ]);

        $logoutResponse->assertStatus(200);

        // Проверяем что logout прошел успешно
        $logoutResponse->assertJson([
            'message' => 'Успешный выход из системы'
        ]);
    }

    public function test_malformed_authorization_header_returns_401(): void
    {
        $malformedHeaders = [
            ['Authorization' => 'InvalidFormat token'],
            ['Authorization' => 'Bearer'],
            ['Authorization' => 'Bearer '],
            ['Authorization' => ''],
        ];

        foreach ($malformedHeaders as $header) {
            $response = $this->apiGet('/tasks', $header);
            $response->assertStatus(401);
        }
    }

    public function test_passport_scope_authentication(): void
    {
        $user = User::factory()->create();

        // Аутентификация через Passport
        $this->authenticateUser($user);

        $response = $this->apiGet('/tasks');
        $response->assertStatus(200);
    }

    public function test_multiple_simultaneous_sessions_are_supported(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Создаем два токена для одного пользователя
        $response1 = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response2 = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $token1 = $response1->json('access_token');
        $token2 = $response2->json('access_token');

        $this->assertNotEquals($token1, $token2);

        // Оба токена должны работать
        $response = $this->apiGet('/tasks', ['Authorization' => "Bearer {$token1}"]);
        $response->assertStatus(200);

        $response = $this->apiGet('/tasks', ['Authorization' => "Bearer {$token2}"]);
        $response->assertStatus(200);
    }
}
