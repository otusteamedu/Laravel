<?php

namespace Vagrant\Ascii\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Vagrant\Ascii\Services\AsciiRenderInterface;

class AsciiController
{
    public function form()
    {
        return View::make("ascii::form");
    }

    public function render(Request $request, AsciiRenderInterface $renderer)
    {
        $image = $request->file("image");
        $type = $request->input("type");

        if ($type == "text") {
            return
                '<pre>' .
                $renderer->renderText($image->path())
                . '</pre>';
        }

        return
            '<div style="max-width: min-content;">' .
            $renderer->renderHtml($image->path())
            . '</div>';
    }
}