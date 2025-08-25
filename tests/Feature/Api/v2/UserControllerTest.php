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

#[Group('feature_api_v2_user_controller')]

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $showProfileUrl;
    protected string $updateNameUrlBase;
    protected string $updateEmailUrlBase;
    protected string $updatePasswordUrlBase;

    public function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->showProfileUrl = route('api.v2.user.showProfile');
        $this->updateNameUrlBase = route('api.v2.user.updateName');
        $this->updateEmailUrlBase = route('api.v2.user.updateEmail');
        $this->updatePasswordUrlBase = route('api.v2.user.updatePassword');
        $this->withHeaders([
            'Accept-Language' => 'ru',
        ]);
    }

    #[Test()]
    public function it_returns_user_profile(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [], 'api-v2');
        $response = $this->getJson($this->showProfileUrl);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ],
            ]);
    }

    #[Test()]
    #[TestWith(['newName', 200])]
    #[TestWith([1111, 422])]
    public function it_updates_user_name(
        string|int $newName,
        int $expectedCode
    ): void {
        $user = User::factory()->create();
        Passport::actingAs($user, [], 'api-v2');
        $response = $this->postJson($this->updateNameUrlBase, [
            'newName' => $newName,
        ]);
        if ($expectedCode === 200) {
            $response->assertJsonPath('data.name', $newName);
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'name' => $newName,
            ]);
        } elseif ($expectedCode === 422) {
            $response->assertJsonValidationErrors(['newName']);
            $this->assertDatabaseMissing('users', [
                'id' => $user->id,
                'name' => $newName,
            ]);
        }
    }

    #[Test()]
    #[TestWith(['newEmail@mail.ru', 200])]
    #[TestWith(['newEmail', 422])]
    public function it_updates_user_email(
        string $newEmail,
        int $expectedCode
    ): void {
        $user = User::factory()->create();
        Passport::actingAs($user, [], 'api-v2');
        $response = $this->postJson($this->updateEmailUrlBase, [
            'newEmail' => $newEmail,
        ]);
        if ($expectedCode === 200) {
            $response->assertJsonPath('data.email', $newEmail);
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'email' => $newEmail,
            ]);
        } elseif ($expectedCode === 422) {
            $response->assertJsonValidationErrors(['newEmail']);
            $this->assertDatabaseMissing('users', [
                'id' => $user->id,
                'email' => $newEmail,
            ]);
        }
    }

    #[Test()]
    #[TestWith(['654321', 200])]
    #[TestWith(['', 422])]
    public function it_updates_user_password(
        string|int $newPassword,
        int $expectedCode
    ): void {
        $user = User::factory()->create();
        Passport::actingAs($user, [], 'api-v2');
        $response = $this->postJson($this->updatePasswordUrlBase, [
            'newPassword' => $newPassword,
        ]);
        if ($expectedCode === 200) {
            $user->refresh();
            $this->assertTrue(Hash::check($newPassword, $user->getPassword()));
        } elseif ($expectedCode === 422) {
            $response->assertJsonValidationErrors(['newPassword']);
            $user->refresh();
            $this->assertFalse(Hash::check($newPassword, $user->getPassword()));
        }
    }
}
