<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('api_auth')]
class AuthTest extends TestCase
{
    use RefreshDatabase;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
        $clientRepository = new ClientRepository();
        $this->client = $clientRepository->createPasswordGrantClient('Test Client', 'users');
    }

    public function test_user_can_obtain_access_token(): void
    {
        $user = User::factory()->create();

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'username' => $user->email,
            'password' => 'password',
            'scope' => '',
        ];
        $response = $this->postJson('/oauth/token', $params);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }

    public function test_user_can_refresh(): void
    {
        $user = User::factory()->create();

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'username' => $user->email,
            'password' => 'password',
            'scope' => '',
        ];
        $response = $this->postJson('/oauth/token', $params);
        $content = json_decode($response->getContent(), true);
        $resfreshToken = $content['refresh_token'];

        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->client->id,
            'refresh_token' => $resfreshToken,
            'scope' => '',
        ];
        $response = $this->postJson('/oauth/token', $params);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'username' => $user->email,
            'password' => 'password',
            'scope' => '',
        ];
        $response = $this->postJson('/oauth/token', $params);
        $content = json_decode($response->getContent(), true);
        $accessToken = $content['access_token'];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $accessToken])->postJson('/api/logout');

        $response->assertStatus(200);
    }
}