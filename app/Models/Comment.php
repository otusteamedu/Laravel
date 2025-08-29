<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
class Comment extends Model
{
    use HasFactory;
    /**
    @return BelongsTo<User,string>
    */
    function author()
    {
        $class = Relation::getMorphedModel('user');
        return $this->belongsTo($class, "user_id");
    }
    /**
    @return BelongsTo<News>
    */
    function news()
    {
        $class = Relation::getMorphedModel('news');
        return $this->belongsTo($class);
    }
    /**
    @return morphMany<Like,string>
    */
    public function likes()
    {
        $class = Relation::getMorphedModel('like');
        return $this->morphMany($class, 'liked');
    }
}