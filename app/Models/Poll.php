<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Poll extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'poll_igor_id',
        'name',
        'description',
        'start_text',
        'end_text',
        'icon',
        'authorized',
        'chart_mode',
        'start_date',
        'end_date'
    ];

    public $timestamps = false;

    public function pollAnswers(): HasMany
    {
        return $this->hasMany(PollAnswer::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order_number');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class)->orderBy('order_number');
    }
}
