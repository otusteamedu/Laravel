<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('password')
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);


        $this->call([
            RoleSeeder::class,
            UserRoleSeeder::class,
            CategorySeeder::class
        ]);
    }
}
