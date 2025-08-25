<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\ValueObjects\Photo\PhotoPath;
use App\Domain\ValueObjects\Photo\PhotoUrl;
use Carbon\Carbon;

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

    public function getURL() 
    {
        return new PhotoUrl($this->url);
    }

    public function getPath() 
    {
        return new PhotoPath($this->path);
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

    public function getCreatedAt(): string 
    {
        $data = Carbon::createFromDate($this->created_at)->format('d.m.Y');
        return $data;
    }

    public function getUpdatedAt(): string 
    {
        $data = Carbon::createFromDate($this->updated_at)->format('d.m.Y');
        return $data;
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\PhotoFactory::new();
    }
}
