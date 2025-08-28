<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    /**
    @return BelongsTo<User>
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
    @return morphMany<Like>
    */
    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }
}