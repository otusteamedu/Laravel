<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\Photo as BusinessModelsPhoto;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
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

    public function getURL(): PhotoUrl
    {
        return new PhotoUrl($this->url);
    }

    public function getPath(): PhotoPath
    {
        return new PhotoPath($this->path);
    }

    public function getIsPreview(): bool
    {
        return $this->is_preview;
    }

    public function getPhotoType(): string
    {
        return $this->photo_type;
    }

    public function getPhotoId(): int
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

    public function toBusinessModel(): ?BusinessBaseModel
    {
        return new BusinessModelsPhoto(
            id: $this->getId(),
            url: $this->getURL(),
            path: $this->getPat(),
            is_preview: $this->getIsPreview(),
            photo_type: $this->getPhotoType(),
            photo_id: $this->getPhotoId(),
            created_at: $this->getCreatedAt()
        );
    }

    protected static function newFactory()
    {
        return \Database\Factories\PhotoFactory::new();
    }
}
