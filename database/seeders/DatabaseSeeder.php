<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory(
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'is_admin' => true,
            ]
        )->create();

        User::factory(5)->create();

        $this->call(
            [
                NewsSeeder::class,
                CommentSeeder::class,
                CommentSeeder::class, // добавление дочерних комментариев
            ]
        );
    }
}
