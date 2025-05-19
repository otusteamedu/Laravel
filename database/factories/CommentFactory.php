<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\News;
use App\Models\Comment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $userId = $user->id ?? User::factory()->create()->id;

        $news = News::inRandomOrder()->first();
        $newsId = $news->id ?? News::factory()->create()->id;

        // Пытаемся найти существующий комментарий для этой новости
        $parentComment = Comment::where('news_id', $newsId)
            ->whereNull('comment_id')  // Берем только комментарии верхнего уровня в качестве родителей
            ->inRandomOrder()
            ->first();

        if ($parentComment) {
            $commentId = $parentComment->id;
        }

        return [
            'user_id' => $userId,
            'comment_id' => $commentId ?? null,
            'news_id' => $newsId,
            'text' => fake()->paragraph,
        ];
    }
}
