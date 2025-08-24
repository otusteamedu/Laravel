<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    @property string $language
    @property string $group
    @property string $date
    @property string $teacher
    @property string $created_at
    @property string $updated_at
*/

class Shedule extends Model
{
    protected $fillable = [
        'language',
        'group',
        'date',
        'teacher',
        'author_id',
        'created_at',
        'updated_at',
    ];
}
