<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use Illuminate\Database\Seeder;
use Database\Seeders\TodoSeeder;
use Database\Seeders\TodoUserSeeder;
use Database\Seeders\TodoCommentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /** Добавляем дефолтного поьзователя */
        User::factory()
            ->has(UserProfile::factory(), 'profile')
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        /** Добавляем пользователей c проектами */
        $this->callWith(UserWithProjectSeeder::class, ['count' => 5]);

        /** Добавляем пользователей к проектам */
        $this->callWith(ProjectUserSeeder::class, ['count' => 20]);

        /** Добавляем задачи с комментариями автора */
        $this->callWith(TodoWithCommentSeeder::class, ['count' => 20]);

        /** Добавляем пользователей к задачам */
        $this->callWith(TodoUserSeeder::class, ['count' => 50]);

        /** Добавляем комментарии к задачам */
        $this->callWith(TodoCommentSeeder::class, ['count' => 100]);
    }
}
