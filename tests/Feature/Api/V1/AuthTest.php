<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends ApiTestCase
{
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => [
                         'id',
                         'name',
                         'email'
                     ],
                     'access_token',
                     'token_type'
                 ]);

        $this->assertEquals('Bearer', $response->json('token_type'));
        $this->assertNotEmpty($response->json('access_token'));
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->apiPost('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Неверные учетные данные'
                 ]);
    }

    public function test_login_requires_email(): void
    {
        $response = $this->apiPost('/auth/login', [
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_login_requires_password(): void
    {
        $response = $this->apiPost('/auth/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_login_requires_valid_email_format(): void
    {
        $response = $this->apiPost('/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        
        $response = $this->authenticatedApiPost('/auth/logout', [], $user);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Успешный выход из системы'
                 ]);
    }

    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->apiPost('/auth/logout');

        $response->assertStatus(401);
    }

    public function test_user_cannot_access_protected_routes_without_authentication(): void
    {
        $response = $this->apiGet('/tasks');

        $response->assertStatus(401);
    }

    public function test_user_can_access_protected_routes_with_authentication(): void
    {
        $user = User::factory()->create();

        $response = $this->authenticatedApiGet('/tasks', $user);

        // Ожидаем успешный ответ (200) или отсутствие данных с правильной структурой
        $response->assertStatus(200);
        $this->assertJsonPaginationStructure($response);
    }
}
