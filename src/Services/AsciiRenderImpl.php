<?php

namespace Vagrant\Ascii\Services;

use Img2Ascii\Processor;

class AsciiRenderImpl implements AsciiRender
{
    public function renderToText($filePathOrFile)
    {
        $processor = new Processor($filePathOrFile);
        return '<pre>' . $processor->asciify(config("ascii.pixel_size", 5))->result('#@Mm+:-.')->getText() . '</pre>';
    }
    public function renderToColoredText($filePathOrFile)
    {
        $processor = new Processor($filePathOrFile);
        return '<div>' . $processor->asciify(config("ascii.pixel_size", 5))->colorResult('#')->getText() . '</div>';
    }
}