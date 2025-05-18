<?php

namespace App\Models;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $todo_id
 * @property integer $user_id
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Todo $todo
 * @property User $user
 */
class TodoComment extends Model
{
    /** @use HasFactory<\Database\Factories\TodoCommentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'todo_comments';

    public $fillable = ['todo_id', 'user_id', 'comment'];

    /**
     * Задача к которой добавлен комментарий
     * @return BelongsTo<Todo, TodoComment>
     */
    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }

    /**
     * Автор комментария
     * @return BelongsTo<User, TodoComment>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
