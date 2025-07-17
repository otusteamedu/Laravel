<?php

namespace App\EloquentModels;

class Video extends BaseModel
{
    /**
     * Class Video
     *
     * @property int $id
     * @property text $url
     * @property text $path
     * @property boolean $is_preview
     * @property string $video_type
     * @property int $video_id
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipe()
    {
        return $this->morphTo('video');
    }

    public function getURL() 
    {
        return $this->url;
    }

    public function getPath() 
    {
        return $this->path;
    }

    public function getVideoType() 
    {
        return $this->photo_type;
    }

    public function getVideoId() 
    {
        return $this->photo_id;
    }

    public function getCreatedAt() 
    {
        return $this->created_at;
    }

    public function getUpdatedAt() 
    {
        return $this->updated_at;
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\VideoFactory::new();
    }
}
