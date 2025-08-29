<?php
namespace Tests\Unit\Users\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Repositories\Users\UserRepositoryInterface;
use App\Services\Commands\DeleteUser\Command;
use App\Services\Commands\DeleteUser\Handler;
use App\Services\Exceptions\Users\UserNotFoundException;
use App\Models\User;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_user()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $user = new User();
        $user->id = 1;
        $user->name = 'Пользователь для удаления';
        $user->email = 'delete@example.com';
        $user->is_admin = false;

        $repository->shouldReceive('find')->with(1)->andReturn($user);
        $repository->shouldReceive('delete')->with($user)->andReturn(true);

        $handler = new Handler($repository);

        $command = new Command(id: 1);

        $result = $handler->handle($command);

        $this->assertTrue($result);
    }

    public function test_throws_exception_when_user_not_found()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        // Пользователь не найден
        $repository->shouldReceive('find')->with(999)->andReturn(null);

        $handler = new Handler($repository);

        $command = new Command(id: 999);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Пользователь не найден');

        $handler->handle($command);
    }

    public function test_returns_false_when_delete_fails()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $user = new User();
        $user->id = 2;
        $user->name = 'Пользователь с ошибкой удаления';
        $user->email = 'faildelete@example.com';
        $user->is_admin = false;

        $repository->shouldReceive('find')->with(2)->andReturn($user);
        $repository->shouldReceive('delete')->with($user)->andReturn(false);

        $handler = new Handler($repository);

        $command = new Command(id: 2);

        $result = $handler->handle($command);

        $this->assertFalse($result);
    }

    public function test_creates_command_with_id()
    {
        $command = new Command(id: 42);

        $this->assertEquals(42, $command->id);
    }

    public function test_creates_command_with_different_ids()
    {
        $command1 = new Command(id: 1);
        $command2 = new Command(id: 999);
        $command3 = new Command(id: 123456);

        $this->assertEquals(1, $command1->id);
        $this->assertEquals(999, $command2->id);
        $this->assertEquals(123456, $command3->id);
    }
}
