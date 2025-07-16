<?php

namespace App\TodoApp\Infrastructure\Eloquent\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property string $name
 * @property ?string $description
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property ProjectUser[] $projectUsers
 */

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'projects';

    public $fillable = ['name', 'description'];

    public function projectUsers(): HasMany
    {
        return $this->hasMany(ProjectUser::class);
    }
}
