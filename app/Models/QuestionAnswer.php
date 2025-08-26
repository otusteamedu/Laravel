<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class QuestionAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'poll_id',
        'question_id',
        'order_number',
        'name',
        'name_for_chart',
        //'comment',
        'self',
        'selected',
        'icon',
        'excluded_order_numbers',
        'end_text'
    ];

    public $timestamps = false;

    protected $casts = [
        'self' => 'boolean',
        'selected' => 'boolean',
        'excluded_order_numbers' => 'array'
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function pollAnswers(): HasMany
    {
        return $this->hasMany(PollAnswer::class, 'answer_id');
    }
}
