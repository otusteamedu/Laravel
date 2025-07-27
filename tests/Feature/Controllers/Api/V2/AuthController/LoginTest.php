<?php

namespace Feature\Controllers\Api\V2\AuthController;

use App\Services\OAuth\UseCases\Commands\Login\Handler as LoginHandler;
use App\Services\OAuth\UseCases\Commands\Login\Command as LoginCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Mockery;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Services\OAuth\Exceptions\InvalidCredentialsException;

#[Group('api')]
#[Group('api-auth')]
class LoginTest extends TestCase
{
    use RefreshDatabase;
    private const URL = 'api/v2/login';
    private const GUARD = 'api_v2';

    private const EMAIL = 'testuser@example.com';
    private const PASSWORD = 'password123';

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


    public function test_login_success(): void
    {
        $mockLoginHandler = Mockery::mock(LoginHandler::class);
        $mockLoginHandler->shouldReceive('handle')
                            ->once()
                            ->with(Mockery::on(function ($command) {
                                return $command instanceof LoginCommand
                                       && $command->email === self::EMAIL
                                       && $command->password ===self::PASSWORD;
                            }))
                            ->andReturn(['token' => 'fake-jwt-token']);

        $this->app->instance(LoginHandler::class, $mockLoginHandler);

        $response = $this->postJson(self::URL, [
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['token']);
    }


    public function test_login_validation_error_body_empty(): void
    {
        $response = $this->postJson(self::URL, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email', 'password']);
    }


    public function test_login_validation_error_invalid_email(): void
    {
        $response = $this->postJson(self::URL, [
            'email' => 'invalid-email',
            'password' => self::PASSWORD,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email']);
    }


    public function test_login_invalid_credentials(): void
    {
        $mockHandler = Mockery::mock(LoginHandler::class);
        $mockHandler->shouldReceive('handle')
                    ->once()
                    ->andThrow(new InvalidCredentialsException('Invalid credentials'));

        $this->app->instance(LoginHandler::class, $mockHandler);

        $response = $this->postJson(self::URL, [
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson(['error' => 'Invalid credentials']);
    }

}
