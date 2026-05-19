<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;

class Post extends Model
{
    use SoftDeletes;
    use Rememberable;

    public $timestamps = true;

    protected $fillable = [
        "title",
        "text",
        "is_draft",
        "author_id"
    ];

    public $casts = ['is_draft' => 'boolean'];

    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

}
