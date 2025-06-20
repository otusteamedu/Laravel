<?php

namespace Vagrant\Ascii\Controllers;

use Illuminate\Http\Request;
use Vagrant\Ascii\Services\AsciiRender;

class AsciiController
{
    public function form()
    {
        return view("ascii::form");
    }

    public function render(Request $request, AsciiRender $render)
    {
        $file = $request->file('file');
        $type = $request->input('type');
        $colorized = $type === "to colored ASCII";

        if ($colorized) {
            return $render->renderToColoredText($file);
        }

        return $render->renderToText($file);
    }
}
