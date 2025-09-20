<?php

namespace App\Domain\Factories\Photo;

use App\Domain\BusinessModels\Photo;
use App\Domain\ValueObjects\Photo\PhotoPath;
use App\Domain\ValueObjects\Photo\PhotoUrl;

class PhotoFactory
{
    public static function make(
        string $url,
        string $path,
        bool $isPreview,
        string $photoType,
        string $photoId,
    ): Photo {
        $photoUrl = new PhotoUrl($url);
        $photoPath = new PhotoPath($path);

        return new Photo($photoUrl, $photoPath, $isPreview, $photoType, $photoId);
    }
}
