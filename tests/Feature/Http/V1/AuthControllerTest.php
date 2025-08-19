<?php

namespace Tests\Feature\Http\V1;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('auth')]
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $userPassword = 'password123';
    protected $accessToken;
    protected $refreshToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make($this->userPassword),
        ]);

        // Аутентификация пользователя
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => $this->userPassword,
        ]);

        $this->accessToken = $response->json('access_token');
        $this->refreshToken = $response->json('refresh_token');
    }

    public function test_user_login_successful()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => $this->userPassword,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
                'refresh_expires_in',
            ]);
    }

    public function test_user_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_refresh_token_successful()
    {
        $response = $this->postJson('/api/auth/refresh-token', [
            'refresh_token' => $this->refreshToken,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
                'refresh_expires_in',
            ]);
    }


    public function test_user_login_with_nonexistent_email()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => $this->userPassword,
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_refresh_token_with_invalid_token_fails()
    {
        $response = $this->postJson('/api/auth/refresh-token', [
            'refresh_token' => 'invalidtoken',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Invalid or expired refresh token']);
    }

    public function test_get_authenticated_user_data()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]);
    }

    public function test_logout_successful()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);

        // Проверяем, что токен больше не действителен
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/auth/me');

        $response->assertStatus(401);
    }

    public function test_refresh_access_token_successful()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);

        // Проверяем, что новый токен работает
        $newAccessToken = $response->json('access_token');

        $meResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $newAccessToken,
        ])->postJson('/api/auth/me');

        $meResponse->assertStatus(200)
            ->assertJson([
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]);
    }


    public function test_refresh_access_token_after_logout_fails()
    {
        // Сначала выходим
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/auth/logout');

        // Пытаемся обновить токен после выхода
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }


}
