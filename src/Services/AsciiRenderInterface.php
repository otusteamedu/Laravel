<?php

namespace Vagrant\Ascii\Services;

use Illuminate\Http\File;

interface AsciiRenderInterface
{
    public function renderText(string $imagePath): string;
    public function renderHtml(string $imagePath): string;
}