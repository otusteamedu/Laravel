<?php

return [
	'font_path' => env('USER_LOGO_FONT_PATH', ''),
	'size' => env('USER_LOGO_SIZE', 32), // размера изображения
	'font_size_ratio' => env('USER_LOGO_FONT_SIZE_RATIO', 0.4), // размер шрифта как доля от размера изображения
	'background_color' => env('USER_LOGO_BACKGROUND_COLOR', '4682b4'), // цвет фона (hex без #)
	'text_color' => env('USER_LOGO_TEXT_COLOR', 'ffffff'), // цвет текста (hex без #)
];
