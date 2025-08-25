<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\ValueObjects\Lang;
use App\Infrastructure\Helpers\LocaleHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getId()
    {
        return $this->id;
    }

    public function getLang(): Lang
    {
        $lang = new Lang(LocaleHelper::getLocale());
        return $lang;
    }
}
