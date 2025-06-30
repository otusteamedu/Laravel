<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $text
 * @property string $dedline
 * @property int $author_id
 */
class Todolist extends Model
{
    /** @use HasFactory<\Database\Factories\TodolistFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'dedline',
        'author_id',
    ];
}
