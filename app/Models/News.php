<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends BaseModel
{
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
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
    
}
