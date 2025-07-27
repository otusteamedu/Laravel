<?php

namespace Feature\Controllers\Api\V2\AuthController;

use App\Models\User;
use App\Services\OAuth\UseCases\Commands\Register\Command as RegisterCommand;
use App\Services\OAuth\UseCases\Commands\Register\Handler as RegisterHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
//use PHPUnit\Framework\Attributes\DataProvider;
//use PHPUnit\Framework\Attributes\TestWith;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

#[Group('api')]
#[Group('api-auth')]
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private const NAME = 'Test User';
    private const EMAIL = 'testuser@example.com';
    private const PASSWORD = 'password123';

    private const URL = 'api/v2/register';

    protected function setUp(): void {
        parent::setUp();

        Mail::fake();
        Event::fake();
    }

    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    public function test_register_success(): void
    {
        $mockRegisterHandler = Mockery::mock(RegisterHandler::class);
        $mockRegisterHandler->shouldReceive('handle')
                            ->once()
                            ->with(Mockery::on(function ($command) {
                                return $command instanceof RegisterCommand
                                       && $command->name === self::NAME
                                       && $command->email === self::EMAIL
                                       && $command->password ===self::PASSWORD;
                            }))
                            ->andReturn(['token' => 'fake-jwt-token']);
        /*$mockRegisterHandler = $this->createMock(RegisterHandler::class);
        $mockRegisterHandler->expects($this->once())
                            ->method('handle')
                            ->with($this->callback(function ($command) use ($name, $email, $password) {
                                return $command instanceof RegisterCommand
                                       && $command->name === $name
                                       && $command->email === $email
                                       && $command->password === $password;
                            }))
                            ->willReturn(['token' => 'fake-jwt-token']);*/


        $this->app->instance(RegisterHandler::class, $mockRegisterHandler);

        $response = $this->postJson(self::URL, [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['token']);
    }

    public function test_register_success_creates_user(): void
    {
        $this->postJson(self::URL, [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => self::EMAIL,
        ]);
    }

    public function test_register_error_validation(): void
    {
        $response = $this->postJson(self::URL, [
            'name' => self::NAME,
            'email' => self::EMAIL,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('password');

        $this->assertDatabaseMissing('users', [
            'email' => self::EMAIL,
        ]);
    }

    public function test_register_error_validation_email_unique()
    {
         User::factory()->create(
             [
                 'email' => self::EMAIL
             ]
         );

        $response = $this->postJson(self::URL, [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('email');

        $response->assertJsonFragment([
                                          'email' => ['The email has already been taken.'],
                                      ]);
    }

    public function test_register_returns_401_on_exception()
    {
        $mockRegisterHandler = Mockery::mock(RegisterHandler::class);
        $mockRegisterHandler->shouldReceive('handle')
                            ->once()
                            ->andThrow(new \Exception('Unauthorized access'));
        /*$mockRegisterHandler = $this->createMock(RegisterHandler::class);
        $mockRegisterHandler->expects($this->once())
                            ->method('handle')
                            ->will($this->throwException(new \Exception('Unauthorized access')));*/

        $this->app->instance(RegisterHandler::class, $mockRegisterHandler);

        $response = $this->postJson(self::URL, [
            'name' => self::NAME,
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJson([
                                  'message' => 'Unauthorized access',
                              ]);
    }
}
