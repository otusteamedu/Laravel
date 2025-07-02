<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Задача принадлежит одному пользователю.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
