<?php

namespace Tests\Feature\Oauth;

//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Auth\AuthManager;
use PHPUnit\Framework\Attributes\Group;
use App\Models\User;

class OauthControllerTest extends TestCase
{
    use DatabaseTruncation;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:client --env=testing --silent');
        Artisan::call('passport:client --personal --silent');


    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Проверка что создание нового пользователя проходит успешно
     */
    #[Group(name: "oauth_register")]
    public function test_register_success()
    {
        $dataToRegister = [
            'email' => 'testingOauth@mail.com',
            'password' => 'testingOauth@mail.com',
            'name' => 'testingOauth',
            'second_name' => 'testingOauth',
            'last_name' => 'testingOauth',
            'organization' => 'testingOauth',
            'user_role' => 'emp'
        ];

        $response = $this->postJson('http://localhost/api/oauth/register', $dataToRegister);

        $response->assertStatus(Response::HTTP_OK, $response);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['email' => 'testingOauth@mail.com']);
    }

    /**
     * Проверка что при создании нового пользователя в случае ошибок валидации выдается нужный ответ
     */
    #[Group(name: "oauth_register")]
    public function test_register_validation_errors()
    {
        $dataToRegister = [
            'email' => 'testingOauth@mail.com',
            //'password' => 'testingOauth@mail.com',
            'name' => 'testingOauth',
            'second_name' => 'testingOauth',
            'last_name' => 'testingOauth',
            'organization' => 'testingOauth',
            'user_role' => 'emp'
        ];

        $response = $this->postJson('http://localhost/api/oauth/register', $dataToRegister);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR, $response);
        $this->assertDatabaseCount('users', 0);
        $response->assertJson(['error' =>  "Password is required"]);
    }

    /**
     * Проверка что подключение к апи Passport пользователя проходит успешно
     */
    #[Group(name: "oauth_login")]
    public function test_login_success()
    {
        $dataToLogin = [
            'email' => 'testingOauth@mail.com',
            'password' => 'testingOauth@mail.com',
        ];

        $user = User::create(
            ['email' => 'testingOauth@mail.com',
            'password' => 'testingOauth@mail.com',
            'name' => 'testingOauth',
            'second_name' => 'testingOauth',
            'last_name' => 'testingOauth',
            'organization' => 'testingOauth',
            'user_role' => 'emp'
            ]
        );

        $response = $this->postJson('http://localhost/api/oauth/login', $dataToLogin);

        $this->assertDatabaseCount('users', 1);
        $response->assertOk();
        $this->assertArrayHasKey('token', $response->json());
        $this->assertDatabaseCount('oauth_access_tokens', 1);
    }

    /**
     * Проверка что при ошибках валидации при подключении к апи Passport выдается правильный ответ
     */
    #[Group(name: "oauth_login")]
    public function test_login_validation_error()
    {
        $dataToLogin = [
            //'email' => 'testingOauth@mail.com',
            'password' => 'testingOauth@mail.com',
        ];

        $user = User::create(
            ['email' => 'testingOauth@mail.com',
                'password' => 'testingOauth@mail.com',
                'name' => 'testingOauth',
                'second_name' => 'testingOauth',
                'last_name' => 'testingOauth',
                'organization' => 'testingOauth',
                'user_role' => 'emp'
            ]
        );

        $response = $this->postJson('http://localhost/api/oauth/login', $dataToLogin);

        $this->assertDatabaseCount('users', 1);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY, $response);
        $response->assertJson(['message'=> 'Email is required', 'errors' => ['email' => ['Email is required']]]);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertDatabaseCount('oauth_access_tokens', 0);
    }
}
