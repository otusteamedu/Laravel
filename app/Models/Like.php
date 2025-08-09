<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ["user_id"];
    public function likedOne()
    {
        return $this->morphTo("liked");
    }

    public function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}