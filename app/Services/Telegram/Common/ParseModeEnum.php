<?php

namespace App\Services\Telegram\Common;

enum ParseModeEnum: string
{
    case MACKDOWN = 'MarkdownV2';
    case HTML = 'HTML';
}
