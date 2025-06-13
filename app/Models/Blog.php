<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    @property string $title
    @property string $preview
    @property string $text
    @property string $created_at
    @property string $updated_at
*/

class Blog extends Model
{
    protected $fillable = [
        'title',
        'preview',
        'text',
        'author_id',
        'created_at',
        'updated_at',
    ];
}
