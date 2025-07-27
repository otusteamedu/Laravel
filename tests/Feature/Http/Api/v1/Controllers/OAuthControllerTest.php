<?php

namespace Tests\Feature\Http\Api\V1\Controllers;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Testing\Fluent\AssertableJson;

#[Group('api')]
class OAuthControllerTest extends TestCase
{
    public function testApiRegisterSuccess(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->json('POST', '/api/v1/oauth/register', $userData);

        $response
            ->assertStatus(201)
            ->assertJson([
                "message" => "User registerd successfully",
            ]);

        $this->assertDatabaseHas(User::class, ['email' => $userData['email']]);
    }

    public function testApiRegisterFailed(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password1'
        ];

        $response = $this->json('POST', '/api/v1/oauth/register', $userData);

        $response->assertJson(
            fn(AssertableJson $json) =>
            $json->hasAll(['message', 'errors.password'])
        );
    }

    public function testApiLoginSuccess(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/v1/oauth/login', $credentials);

        $response->assertJson(
            fn(AssertableJson $json) =>
            $json->has('token')
        );
    }

    public function testApiLoginFailed(): void
    {
        $credentials = [
            'email' => '0@0.com',
            'password' => 'password1',
        ];

        $response = $this->json('POST', '/api/v1/oauth/login', $credentials);

        $response
            ->assertStatus(401)
            ->assertJson([
                "message" => "The provided credentials are incorrect.",
            ]);
    }

    public function testApiGetAuthorizedUserSuccess(): void
    {
        /** @var  User $user */
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->actingAs($user, 'api')->json('GET', '/api/v1/oauth/user');

        $response
            ->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) use ($user) {
                    $json->has('data', function (AssertableJson $json) use ($user) {
                        $json->where('id', $user->id)
                            ->where('name', $user->name)
                            ->where('email', $user->email)
                            ->etc();
                    });
                }
            );
    }

    public function testApiLogoutSuccess(): void
    {
        /** @var  User $user */
        $user = User::factory()->create(['password' => 'password']);

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/v1/oauth/login', $credentials);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $response->json()['token'],
        ])->json('POST', '/api/v1/oauth/logout');

        $response
            ->assertStatus(200)
            ->assertJson([
                "message" => "Logged out successfully",
            ]);
    }
}
