<?php
namespace Tests\Unit\Users\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Repositories\Users\UserRepositoryInterface;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use App\Services\Commands\UpdateUser\Command;
use App\Services\Commands\UpdateUser\Handler;
use App\Services\Exceptions\Users\UserNotFoundException;
use App\Services\DTO\Users\UserDTO;
use App\Models\User;
use Carbon\Carbon;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_user_with_password()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        
        $user = new User();
        $user->id = 1;
        $user->name = 'Старое Имя';
        $user->email = 'old@example.com';
        $user->is_admin = false;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();
        $user->email_verified_at = null;

        $repository->shouldReceive('find')->with(1)->andReturn($user);
        $passwordHasher->shouldReceive('hash')->with('newpassword')->andReturn('hashed_newpassword');
        $repository->shouldReceive('save')->with($user)->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        $command = new Command(
            id: 1,
            name: 'Новое Имя',
            email: 'new@example.com',
            isAdmin: true,
            password: 'newpassword'
        );

        $result = $handler->handle($command);

        $this->assertInstanceOf(UserDTO::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Новое Имя', $result->name);
        $this->assertEquals('new@example.com', $result->email);
        $this->assertTrue($result->isAdmin);
    }

    public function test_can_update_user_without_password()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);
        
        $user = new User();
        $user->id = 2;
        $user->name = 'Старое Имя';
        $user->email = 'old@example.com';
        $user->is_admin = true;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();
        $user->email_verified_at = Carbon::now();

        $repository->shouldReceive('find')->with(2)->andReturn($user);
        // Пароль не передан, поэтому hash не должен вызываться
        $passwordHasher->shouldNotReceive('hash');
        $repository->shouldReceive('save')->with($user)->andReturn(true);

        $handler = new Handler($repository, $passwordHasher);

        $command = new Command(
            id: 2,
            name: 'Обновленное Имя',
            email: 'updated@example.com',
            isAdmin: false
            // password не указан - null по умолчанию
        );

        $result = $handler->handle($command);

        $this->assertInstanceOf(UserDTO::class, $result);
        $this->assertEquals(2, $result->id);
        $this->assertEquals('Обновленное Имя', $result->name);
        $this->assertEquals('updated@example.com', $result->email);
        $this->assertFalse($result->isAdmin);
    }

    public function test_throws_exception_when_user_not_found()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);
        $passwordHasher = Mockery::mock(PasswordHasherInterface::class);

        // Пользователь не найден
        $repository->shouldReceive('find')->with(999)->andReturn(null);

        $handler = new Handler($repository, $passwordHasher);

        $command = new Command(
            id: 999,
            name: 'Несуществующий',
            email: 'notfound@example.com'
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Пользователь не найден');

        $handler->handle($command);
    }
}
