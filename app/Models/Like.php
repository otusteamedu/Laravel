<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
class Like extends Model
{
    protected array $fillable = ["user_id"];
    /**
    @return morphTo<string>
    */
    public function likedOne()
    {
        return $this->morphTo("liked");
    }
    /**
    @return morphTo
    */
    public function liked()
    {
        return $this->morphTo();
    }
    /**
    @return belongsTo<User,string>
    */
    public function author()
    {
        $class = Relation::getMorphedModel('user');
        return $this->belongsTo($class, "user_id");
    }
}