<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Watson\Rememberable\Rememberable;

class Post extends BaseModel
{
    use SoftDeletes, Rememberable;

    use Searchable;

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

    function preview()
    {
        return $this->hasOne(PostPreview::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }

    public function toSearchableArray(): array
    {
        $this->load('author', 'comments', 'comments.author');
        $array = $this->toArray();

        $array['search_me'] = true;

        return $array;
    }
}
