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
        return $this->belongsTo(User::class, "user_id");
    }
    /**
    @return BelongsTo<News>
    */
    function news()
    {
        return $this->belongsTo(News::class);
    }
    /**
    @return morphMany<Like,string>
    */
    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }
}