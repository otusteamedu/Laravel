<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;

class News extends BaseModel
{
  
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
        $class = Relation::getMorphedModel('newsPreview');
        return $this->hasOne($class);
    }
    /**
    @return hasMany<Comment>
    */
    public function comments()
    {
        $class = Relation::getMorphedModel('comment');
        return $this->hasMany($class);
    }
    /**
    @return morphMany<Like,string>
    */
    public function likes()
    {
        $class = Relation::getMorphedModel('like');
        return $this->morphMany($class, 'liked');
    }
    /**
    @return belongsTo<User,string>
    */
    public function author()
    {
        $class = Relation::getMorphedModel('user');
        return $this->belongsTo($class, 'user_id');
    }    
}