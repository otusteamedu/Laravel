<?php

namespace Tests\Feature\Http\V1;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;


#[Group('personal')]
class PersonalCabinetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $accessToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        UserProfile::factory()->create(['user_id' => $this->user->id]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->accessToken = $response->json('access_token');

    }

    public function test_get_personal_cabinet_data()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/personal-cabinet');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'profile' => ['phone', 'address', 'birth_date', 'about'],
            ]);
    }

    public function test_update_personal_cabinet_successful()
    {
        $updateData = [
            'name' => 'New Name',
            'phone' => '1234567890',
            'address' => 'New Address',
            'birth_date' => '1990-01-01',
            'about' => 'Some info about me',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson('/api/v1/personal-cabinet', $updateData);

        //$response->ddJson();

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profile updated successfully',
                'user' => [
                    'name' => 'New Name',
                    'profile' => [
                        'phone' => '1234567890',
                        'address' => 'New Address',
                    ],
                ],

            ]);

        /*
        $response->assertJsonStructure(['data' => ['email', 'name']]);
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('data')->first(
                fn (AssertableJson $json) =>
                $json->has('email')
                    ->where('phone', $updateData['phone'])
                    ->where('name', $updateData['name'])
                    ->etc()
            ));
        */
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'New Name',
        ]);


        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $this->user->id,
            'phone' => '1234567890',
        ]);
    }

    public function test_delete_account_successful()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->deleteJson('/api/v1/personal-cabinet');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Account deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        $this->assertDatabaseMissing('user_profiles', ['user_id' => $this->user->id]);
    }
}
