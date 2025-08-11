<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsPreview extends Model
{
    public $timestamps = false;

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}