<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Photo\PhotoPath;
use App\Domain\ValueObjects\Photo\PhotoUrl;

class Photo extends BaseModel implements BusinessModelsInterface
{
    private PhotoUrl $url;
    private PhotoPath $path;
    private bool $is_preview;
    private string $photo_type;
    private int $photo_id;
    private ?string $created_at;

    public function __construct(
        PhotoUrl $url,
        PhotoPath $path,
        bool $is_preview,
        string $photo_type,
        int $photo_id,
        ?int $id = null,
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->url = $url;
        $this->path = $path;
        $this->is_preview = $is_preview;
        $this->photo_type = $photo_type;
        $this->photo_id = $photo_id;
        $this->created_at = $created_at;
    }

    public function getUrl(): PhotoUrl
    {
        return $this->url;
    }

    public function getPath(): PhotoPath
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

    public function updateUrl(PhotoUrl $newUrl): void
    {
        if ($this->url === $newUrl) {
            throw new NotValidItemDomainException("Новый URL совпадает со старым");
        }
        $this->url = $newUrl;
    }

    public function updatePath(PhotoPath $newPath): void
    {
        if ($this->path === $newPath) {
            throw new NotValidItemDomainException("Новый путь совпадает со старым");
        }
        $this->path = $newPath;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function toArray(): array
    {
        $array = [

        ];
        return $array;
    }
}
