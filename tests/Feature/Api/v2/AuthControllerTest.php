<?php

namespace Tests\Feature\Api\v2;

use App\Infrastructure\EloquentModels\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group('feature_api_v2_auth_controller')]

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $loginUrlBase;
    protected string $registerUrlBase;

    public function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->loginUrlBase = route('api.v2.login');
        $this->registerUrlBase = route('api.v2.register');
        $this->withHeaders([
            'Accept-Language' => 'ru',
        ]);
    }

    #[Test()]
    public function it_registers_user_successfully(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson($this->registerUrlBase, $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id','name','email','created_at','updated_at'],
                     'message'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test()]
    public function it_fails_registration_with_invalid_email(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => 'password123',
        ];
        $response = $this->postJson($this->registerUrlBase, $payload);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    #[Test()]
    public function it_fails_registration_with_existing_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $payload = [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
        $response = $this->postJson($this->registerUrlBase, $payload);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }
}
