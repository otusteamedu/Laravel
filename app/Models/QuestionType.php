<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    public $timestamps = false;

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'type_id');
    }
}
