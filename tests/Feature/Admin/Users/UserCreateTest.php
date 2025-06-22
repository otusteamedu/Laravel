<?php

namespace Tests\Feature\Admin\Users;

use Tests\Feature\Admin\AdminTestCase;
use App\Services\Commands\CreateUser\Handler;
use App\Services\Exceptions\Users\UserEmailAlreadyExistsException;
use App\Services\Exceptions\Users\UserSaveException;

class UserCreateTest extends AdminTestCase
{
    public function test_admin_can_open_create_user_form()
    {
        $response = $this->asAdmin()->get(route('admin.users.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
    }

    public function test_admin_can_create_new_user()
    {
        $userData = [
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivanov@laraveltest.io',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => false,
        ];

        $response = $this->asAdmin()
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));

        // Проверяем что в базе появился новый пользователь (без пароля и подтверждения)
        $this->assertDatabaseHas('users', [
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivanov@laraveltest.io',
            'is_admin' => false,
        ]);

        $response->assertSessionHas('success', "Пользователь 'Иванов Иван Иванович' успешно создан");
    }

    public function test_unauthorized_user_redirected_to_login()
    {
        $this->assertGuestRedirectedToLogin('admin.users.create');
    }

    public function test_guest_cannot_create_user()
    {
        $this->assertGuestRedirectedToLogin('admin.users.store', 'post', [
            'name' => 'Иванов Иван Иванович',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => false,
        ]);
    }

    public function test_regular_user_cannot_create_user()
    {
        $this->asRegularUser()
            ->post(route('admin.users.store'), [
                'name' => 'Иванов Иван Иванович',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'is_admin' => false,
            ])
            ->assertStatus(403);
    }

    public function test_create_requires_name()
    {
        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_create_requires_email()
    {
        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'name' => 'Иванов Иван Иванович',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_create_requires_password()
    {
        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'name' => 'Иванов Иван Иванович',
                'email' => 'test@example.com',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_create_requires_password_confirmation()
    {
        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'name' => 'Иванов Иван Иванович',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'different',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_create_handles_user_email_already_exists_exception()
    {
        // Мок Handler чтобы выбросить UserEmailAlreadyExistsException
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new UserEmailAlreadyExistsException('test@example.com'));

        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'name' => 'Иванов Иван Иванович',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'is_admin' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', "Пользователь с email 'test@example.com' уже существует");
    }

    public function test_create_handles_user_save_exception()
    {
        // Мок Handler чтобы выбросить UserSaveException
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new UserSaveException('Ошибка сохранения пользователя'));

        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'name' => 'TИванов Иван Иванович',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'is_admin' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Ошибка сохранения пользователя');
    }

    public function test_create_handles_general_exception()
    {
        // Мокаем Handler чтобы выбросить общее исключение
        $this->mock(Handler::class)
            ->shouldReceive('handle')
            ->andThrow(new \Exception('Неожиданная ошибка'));

        $this->asAdmin()
            ->post(route('admin.users.store'), [
                'name' => 'Иванов Иван Иванович',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'is_admin' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Произошла непредвиденная ошибка при создании пользователя. Попробуйте позже.');
    }
}
