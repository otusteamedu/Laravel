<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RoleUserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

/**
 * Pivot таблица связи пользователей и их ролей
 *
 * @method static RoleUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleUser query()
 * @mixin Eloquent
 */
class RoleUser extends Model
{
    /** @use HasFactory<RoleUserFactory> */
    use hasFactory;
    use AsPivot;

    public $timestamps = false;
    public $fillable = [
        'user_id',
        'role_id',
    ];
}
