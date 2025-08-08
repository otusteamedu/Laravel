<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/user/profile');

        $response->assertStatus(200);

        $response->assertJson([
            'user' => [
                'email' => $user->email,
            ],
        ]);
    }


    public function test_guest_cannot_access_profile()
    {
        $response = $this->getJson('/api/v1/user/profile');

        $response->assertStatus(401);
    }
}
