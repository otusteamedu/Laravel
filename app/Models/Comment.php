<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\morphMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;
class Comment extends Model
{
    use HasFactory;
    /**
    @return BelongsTo<User,string>
    */
    #[belongsTo(User::class, "user_id")]
    function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    /**
    @return BelongsTo<News>
    */
    #[belongsTo(News::class)]
    function news()
    {
        return $this->belongsTo(News::class);
    }
    /**
    @return morphMany<Like,string>
    */
    #[morphMany(Like::class, 'liked')]
    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }
}