<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Watson\Rememberable\Rememberable;
class News extends BaseModel
{
    use SoftDeletes,Rememberable;
    public $timestamps = true;

    public $fillable = [
        "name",
        "text",
        "user_id",
        "link",
        "preview",
        'create_at'
    ];

    public $casts = ['user_id' => 'integer'];

    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory;

    function preview()
    {
        return $this->hasOne(NewsPreview::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
