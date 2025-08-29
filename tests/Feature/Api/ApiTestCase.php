<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Illuminate\Support\Str;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $apiVersion = 'v1';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем personal access client для тестов через вставку в БД
        \DB::table('oauth_clients')->insert([
            'id' => 12,
            'name' => 'Test Personal Access Client',
            'secret' => null,
            'provider' => null,
            'redirect'=>'',
            'personal_access_client'=>12,
            'redirect_uris' => '[]',
            'grant_types' => '["personal_access"]',
            'revoked' => false,
            'password_client'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->user = User::factory()->create();
    }

    /**
     * Аутентифицировать пользователя через Passport
     */
    protected function authenticateUser(User $user): User
    {
        $user = $user ?? $this->user;
        Passport::actingAs($user ?? 1);
        return $user;
    }

    /**
     * Отправить GET запрос к API
     */
    protected function apiGet(string $endpoint, array $headers = []): \Illuminate\Testing\TestResponse
    {
        return $this->getJson("/api/{$this->apiVersion}{$endpoint}", $headers);
    }

    /**
     * Отправить POST запрос к API
     */
    protected function apiPost(string $endpoint, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        return $this->postJson("/api/{$this->apiVersion}{$endpoint}", $data, $headers);
    }

    /**
     * Отправить PUT запрос к API
     */
    protected function apiPut(string $endpoint, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        return $this->putJson("/api/{$this->apiVersion}{$endpoint}", $data, $headers);
    }

    /**
     * Отправить DELETE запрос к API
     */
    protected function apiDelete(string $endpoint, array $headers = []): \Illuminate\Testing\TestResponse
    {
        return $this->deleteJson("/api/{$this->apiVersion}{$endpoint}", [], $headers);
    }

    /**
     * Отправить аутентифицированный GET запрос
      */
    protected function authenticatedApiGet(string $endpoint, User $user): \Illuminate\Testing\TestResponse
    {
        $this->authenticateUser($user ?? 1);
        return $this->apiGet($endpoint);
    }

    /**
     * Отправить аутентифицированный POST запрос
     */
    protected function authenticatedApiPost(string $endpoint, array $data = [], User $user): \Illuminate\Testing\TestResponse
    {
        
        $this->authenticateUser($user ?? 1);
        return $this->apiPost($endpoint, $data);
    }

    /**
     * Отправить аутентифицированный PUT запрос
     */
    protected function authenticatedApiPut(string $endpoint, array $data = [], User $user): \Illuminate\Testing\TestResponse
    {
        $this->authenticateUser($user);
        return $this->apiPut($endpoint, $data);
    }

    /**
     * Отправить аутентифицированный DELETE запрос
     */
    protected function authenticatedApiDelete(string $endpoint, User $user): \Illuminate\Testing\TestResponse
    {
        $this->authenticateUser($user);
        return $this->apiDelete($endpoint);
    }
    /**
     * Проверить структуру JSON ответа с ошибкой
     */
    protected function assertJsonErrorStructure(\Illuminate\Testing\TestResponse $response, int $expectedStatus = 400): void
    {
        $response->assertStatus($expectedStatus)
                 ->assertJsonStructure([
                     'message'
                 ]);
    }

    /**
     * Проверить структуру JSON ответа с пагинацией
     */
    protected function assertJsonPaginationStructure(\Illuminate\Testing\TestResponse $response): void
    {
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page'
            ]
        ]);
    }
}
