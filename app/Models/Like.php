<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    @return belongsTo<User,string>
    */
    public function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}