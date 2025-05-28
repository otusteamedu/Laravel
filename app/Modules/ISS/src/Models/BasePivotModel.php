<?php

namespace App\Modules\ISS\src\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasePivotModel extends Pivot  /** Не используется, но может потребоваться при расширении модуля, поэтому оставил */
{
    use softDeletes, HasFactory;

    public $incrementing = true;
}
