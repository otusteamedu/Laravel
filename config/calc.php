<?php

return [
    "enabled" => env("CALC_CONVERTER_ENABLED", true),
    "method" => env("CALC_CONVERTER_METHOD", "calc"),
    "view" => env("CALC_CONVERTER_VIEW", "calc"),
    "eval" => env("CALC_CONVERTER_EVAL", "eval"),
    "render" => env("CALC_CONVERTER_RENDER", "calcpost"),
];