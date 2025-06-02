<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory([
                           'name' => 'admin',
                           'email' => 'admin@example.com',
                       ]
        )->create();

        User::factory([
                          'name' => 'editor',
                          'email' => 'editor@example.com',
                      ]
        )->create();

        User::factory(5)->create();

        $this->call(
            [
                NewsSeeder::class,
                CommentSeeder::class,
                CommentSeeder::class, // добавление дочерних комментариев
                RoleSeeder::class,
            ]
        );
    }
}
