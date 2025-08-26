<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Question extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'poll_id',
        'order_number',
        'required',
        'name',
        'name_for_chart',
        //'description',
        'type_id',
        'min_answer_count',
        'max_answer_count',
        'comment',
        'icon'
    ];

    public $timestamps = false;

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'type_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class)->orderBy('order_number');
    }

    public function pollAnswers(): HasMany
    {
        return $this->hasMany(PollAnswer::class);
    }
}
