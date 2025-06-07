<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $todo_id
 * @property integer $author_id
 * @property string $comment
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property User $author
 */
class TodoComment extends Model
{
    /** @use HasFactory<\Database\Factories\TodoCommentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'todo_comments';

    public $fillable = ['todo_id', 'author_id', 'comment'];

    /**
     * Автор комментария
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, TodoComment>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }
}
