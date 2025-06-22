<?php

namespace Tests\Feature\Admin\Users;

use Tests\Feature\Admin\AdminTestCase;
use App\Models\User;

class UserReadTest extends AdminTestCase
{
    public function test_admin_can_read_users()
    {
        // Создаем несколько пользователей
        $users = User::factory()->count(3)->create();

        // Используем хелпер из базового класса
        $this->assertCanReadResourcesList(
            route('admin.users.index'),
            $users
        );
    }

    public function test_pagination_works_in_users_list()
    {
        // Создаем 15 пользователей
        User::factory()->count(15)->create();

        // Используем хелпер из базового класса
        $this->assertPaginationWorks(
            route('admin.users.index'),
            'users'
        );
    }

    public function test_unauthorized_user_redirected_to_login()
    {
        $this->assertGuestRedirectedToLogin('admin.users.index');
    }
} 