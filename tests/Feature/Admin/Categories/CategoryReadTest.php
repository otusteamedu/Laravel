<?php

namespace Tests\Feature\Admin\Categories;

use Tests\Feature\Admin\AdminTestCase;
use App\Models\Category;

class CategoryReadTest extends AdminTestCase
{
    public function test_admin_can_read_categories()
    {
        // Создаем несколько категорий
        $categories = Category::factory()->count(3)->create();

        // Используем хелпер из базового класса
        $this->assertCanReadResourcesList(
            route('admin.categories.index'),
            $categories
        );
    }

    public function test_pagination_works_in_categories_list()
    {
        // Создаем 15 категорий
        Category::factory()->count(15)->create();

        // Используем хелпер из базового класса
        $this->assertPaginationWorks(
            route('admin.categories.index'),
            'categories'
        );
    }

    public function test_unauthorized_user_redirected_to_login()
    {
        $this->assertGuestRedirectedToLogin('admin.categories.index');
    }
} 