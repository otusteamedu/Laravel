<?php

namespace App\Models;

use App\Models\Project;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property integer $sort
 * @property ?string $deleted_at
 * @property string $color
 * @property Project $project
 */
class TodoStatus extends BaseModel
{
    /** @use HasFactory<\Database\Factories\TodoStatusFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'todo_statuses';

    public $timestamps = false;

    public $fillable = ['project_id', 'name', 'sort', 'color'];

    /**
     * Проект для которого создан статус задачи
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Project, TodoStatus>
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'status_id');
    }
}
