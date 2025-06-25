<?php

namespace My\PackageWithPackages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes, HasFactory;

    //public $timestamps = false; //чтобы отключить
    //public $timestamps = true; //чтобы включить


}
