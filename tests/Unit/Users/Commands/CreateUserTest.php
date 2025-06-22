<?php
namespace Tests\Unit\Users\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Repositories\Users\UserRepositoryInterface;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Services\Commands\CreateUser\Command;
use App\Services\Commands\CreateUser\Handler;
use App\Services\Exceptions\Users\UserEmailAlreadyExistsException;
use App\Services\Exceptions\Users\UserSaveException;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user(){
        // создаем мок репозитория
        $repository = Mockery::mock(UserRepositoryInterface::class);

        // создаем мок для PasswordHasher
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        $passwordHasher->shouldReceive('hash')->with('password')->andReturn('hashed_password');

        // Говорим мок репозиторию, что пользователя с таким email нет
        $repository->shouldReceive('existsByEmail')->with('test@example.io')->andReturn(false);

        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        $command = new Command(
            name: 'Иванов Иван Иванович',
            email: 'test@example.io',
            isAdmin: false,
            password: 'password',
        );

        $result = $handler->handle($command);

        $this->assertTrue($result);
    }

    public function test_can_create_admin_user(){
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        $passwordHasher->shouldReceive('hash')->with('password')->andReturn('hashed_password');

        $repository->shouldReceive('existsByEmail')->with('admin@example.io')->andReturn(false);
        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        // Тестируем создание админа с isAdmin: true
        $command = new Command(
            name: 'Админ Сайта',
            email: 'admin@example.io',
            isAdmin: true,
            password: 'password',
        );

        $result = $handler->handle($command);

        $this->assertTrue($result);
    }

    public function test_can_create_user_with_default_parameters(){
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        $passwordHasher->shouldReceive('hash')->with('password')->andReturn('hashed_password');

        $repository->shouldReceive('existsByEmail')->with('default@example.io')->andReturn(false);
        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        // Тестируем создание пользователя с дефолтными параметрами
        $command = new Command(
            name: 'Тестовый Пользователь',
            email: 'default@example.io',
            password: 'password',
        );

        $result = $handler->handle($command);

        $this->assertTrue($result);
    }

    public function test_can_create_user_without_password(){
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        // Пароль null, поэтому hash не должен вызываться
        $passwordHasher->shouldNotReceive('hash');

        $repository->shouldReceive('existsByEmail')->with('nopass@example.io')->andReturn(false);
        $repository->shouldReceive('save')->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        // Тестируем создание пользователя без пароля (password = null по умолчанию)
        $command = new Command(
            name: 'Без Пароля',
            email: 'nopass@example.io'
        );

        $result = $handler->handle($command);

        $this->assertTrue($result);
    }

    public function test_throws_exception_when_email_already_exists(){
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);

        // Email уже существует
        $repository->shouldReceive('existsByEmail')->with('existing@example.io')->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        $command = new Command(
            name: 'Дублированный Пользователь',
            email: 'existing@example.io',
            password: 'password',
        );

        $this->expectException(UserEmailAlreadyExistsException::class);
        $this->expectExceptionMessage('existing@example.io');

        $handler->handle($command);
    }

    public function test_throws_exception_when_save_fails(){
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        $passwordHasher->shouldReceive('hash')->with('password')->andReturn('hashed_password');

        $repository->shouldReceive('existsByEmail')->with('failsave@example.io')->andReturn(false);
        // Сохранение не удалось
        $repository->shouldReceive('save')->andReturn(false);

        $handler = new Handler($repository, $passwordHasher);

        $command = new Command(
            name: 'Неудачное Сохранение',
            email: 'failsave@example.io',
            password: 'password',
        );

        $this->expectException(UserSaveException::class);
        $this->expectExceptionMessage("Не удалось сохранить пользователя 'Неудачное Сохранение'");

        $handler->handle($command);
    }

}
