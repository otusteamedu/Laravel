<?php

namespace Vagrant\Ascii\Services;

use Illuminate\Http\File;
use Img2Ascii\Processor;

class AsciiRender implements AsciiRenderInterface
{
    public function renderText(string $imagePath): string
    {
        $processor = new Processor($imagePath);

        return $processor
            ->asciify(config("ascii.pixel_size"))
            ->result('#@Mm+:-.')->getText();
    }

    public function renderHtml(string $imagePath): string
    {
        $processor = new Processor($imagePath);

        return $processor
            ->asciify(config("ascii.colored_pixel_size"))
            ->colorResult('#')
            ->getText();
    }
}