<?php

namespace App\Domain\Services\Telegram\ValueObject;

enum ParseModeEnum: string
{
    case MACKDOWN = 'MarkdownV2';
    case HTML = 'HTML';
}
