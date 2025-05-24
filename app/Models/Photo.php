<?php

namespace App\Models;

class Photo extends BaseModel
{
    /**
     * Class Photo
     *
     * @property int $id
     * @property text $url
     * @property text $path
     * @property boolean $is_preview
     * @property string $photo_type
     * @property int $photo_id
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipe() 
    {
        return $this->morphTo('photo');
    }

    public function product() 
    {
        return $this->morphTo('photo');
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getURL() 
    {
        return $this->url;
    }

    public function getPath() 
    {
        return $this->path;
    }

    public function getIsPreview() 
    {
        return $this->is_preview;
    }

    public function getPhotoType() 
    {
        return $this->photo_type;
    }

    public function getPhotoId() 
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
}
