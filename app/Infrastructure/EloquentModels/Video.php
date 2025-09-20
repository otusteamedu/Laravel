<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\BusinessModels\Video as BusinessModelsVideo;
use App\Domain\ValueObjects\Video\VideoPath;
use App\Domain\ValueObjects\Video\VideoUrl;
use Carbon\Carbon;

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

    public function getURL(): VideoUrl
    {
        return new VideoUrl($this->url);
    }

    public function getPath(): VideoPath
    {
        return new VideoPath($this->path);
    }

    public function getIsPreview(): bool
    {
        return $this->is_preview;
    }

    public function getVideoType(): string
    {
        return $this->photo_type;
    }

    public function getVideoId(): int
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
        return new BusinessModelsVideo(
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
        return \Database\Factories\VideoFactory::new();
    }
}
