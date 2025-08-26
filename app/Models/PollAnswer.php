<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PollAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ip',
        'poll_id',
        'question_id',
        'answer_id',
        'comment',
        'self_comment',
        'person_identifier',
        'person_id',
        'department_identifier',
        'employee_id',
        'department_id',
        'created_at'
    ];

    public $timestamps = false;

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(QuestionAnswer::class, 'answer_id');
    }
}
