<?php

declare(strict_types=1);

namespace App\Logging;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class TelegramFormatter implements FormatterInterface
{

    /**
     * @param LogRecord $record
     *
     * @return string
     */
    public function format(LogRecord $record): string
    {
        return sprintf(
            "[%s] %s.%s\n\n%s",
            $record->datetime->format('Y-m-d H:i:s'),
            $record->channel,
            $record->level->name,
            $record->message,
        );
    }

    /**
     * @param array $records
     *
     * @return string
     */
    public function formatBatch(array $records): string
    {
        return implode("\n\n", array_map([$this, 'format'], $records));
    }
}
