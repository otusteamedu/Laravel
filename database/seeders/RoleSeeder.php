<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'description' => 'Administrator with full access']);
        Role::firstOrCreate(['name' => 'editor', 'description' => 'Can create and edit content']);
        Role::firstOrCreate(['name' => 'user', 'description' => 'Regular user']);

        Permission::create(['name' => 'view-category', 'description' => 'Разрешение на просмотр категорий']);
        Permission::create(['name' => 'create-category', 'description' => 'Разрешение на создание категорий']);
        Permission::create(['name' => 'edit-category', 'description' => 'Разрешение на редактирование категорий']);
        Permission::create(['name' => 'delete-category', 'description' => 'Разрешение на удаление категорий']);
        Permission::create(['name' => 'view-product', 'description' => 'Разрешение на просмотр продуктов']);
        Permission::create(['name' => 'create-product', 'description' => 'Разрешение на создание продуктов']);
        Permission::create(['name' => 'edit-product', 'description' => 'Разрешение на редактирование продуктов']);
        Permission::create(['name' => 'delete-product', 'description' => 'Разрешение на удаление продуктов']);

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->permissions()->attach(Permission::whereIn('name', [
            'create-category', 'edit-category', 'delete-category',
            'create-product', 'edit-product', 'delete-product'
        ])->pluck('id'));

        $editorRole = Role::where('name', 'editor')->first();
        $editorRole->permissions()->attach(Permission::whereIn('name', [
            'create-category', 'edit-category',
            'create-product', 'edit-product'
        ])->pluck('id'));
    }
}
