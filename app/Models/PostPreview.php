<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostPreview extends Model
{
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
