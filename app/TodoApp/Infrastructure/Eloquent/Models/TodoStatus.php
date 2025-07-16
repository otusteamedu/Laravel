<?php

namespace App\TodoApp\Infrastructure\Eloquent\Models;

use App\Models\BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property integer $sort
 * @property ?Carbon $deleted_at
 * @property string $color
 */
class TodoStatus extends BaseModel
{
    /** @use HasFactory<\Database\Factories\TodoStatusFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'todo_statuses';

    public $timestamps = false;

    public $fillable = ['project_id', 'name', 'sort', 'color'];
}
