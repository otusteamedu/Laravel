<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'preview',
        'text',
        'created_at',
        'updated_at',
    ];
}
