<?php

namespace App\Domain\Factories\Video;

use App\Domain\BusinessModels\Video;
use App\Domain\ValueObjects\Video\VideoPath;
use App\Domain\ValueObjects\Video\VideoUrl;

class VideoFactory
{
    public static function make(
        string $url,
        string $path,
        bool $isPreview,
        string $photoType,
        string $photoId,
    ): Video {
        $videoUrl = new VideoUrl($url);
        $videoPath = new VideoPath($path);

        return new Video($videoUrl, $videoPath, $isPreview, $photoType, $photoId);
    }
}
