<?php

namespace App\TodoApp\Domain\Services\Telegram;

enum ParseModeEnum: string
{
    case MACKDOWN = 'MarkdownV2';
    case HTML = 'HTML';
}
