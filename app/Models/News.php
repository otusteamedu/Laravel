<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends BaseModel
{
    use SoftDeletes;
    public bool $timestamps = true;

    public array $fillable = [
        "name",
        "text",
        "user_id",
        "link",
        "preview",
        'create_at'
    ];

    public array $casts = ['user_id' => 'integer'];

    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory;

    /**
    @return hasOne<NewsPreview>
    */
    function preview()
    {
        return $this->hasOne(NewsPreview::class);
    }
    /**
    @return hasMany<Comment>
    */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
    @return morphMany<Like,string>
    */
    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }
    /**
    @return belongsTo<User,'string'>
    */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }    
}