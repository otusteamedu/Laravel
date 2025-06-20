<?php

namespace Vagrant\Ascii\Services;

interface AsciiRender
{
    public function renderToText($filePathOrFile);
    public function renderToColoredText($filePathOrFile);
}