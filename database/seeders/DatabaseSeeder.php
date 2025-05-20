<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Image;
use App\Models\RoleUser;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $firstUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '79237000000',
        ]);
        RoleUser::create([
            'user_id' => $firstUser->id,
            'role_id' => 1,
        ]);

        RoleUser::factory(9)->create();
        Image::factory(10)->create();
    }
}
