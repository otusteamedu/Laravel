<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Kirill',
            'email' => 'kirill@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
