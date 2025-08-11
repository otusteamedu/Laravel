<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    function news()
    {
        return $this->belongsTo(News::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }
}