<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $todolist_id
 * @property int $author_id
 * @property string $text
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'todolist_id',
        'author_id',
        'text',
    ];
}
