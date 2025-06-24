<?php
namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Level;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHandler extends AbstractProcessingHandler
{
    private string $botToken;
    private string $chatId;

    public function __construct(string $botToken, string $chatId, int|string|Level $level = Level::Error, bool $bubble = true)
    {
        $this->botToken = $botToken;
        $this->chatId = $chatId;
        
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $message = $this->formatMessage($record);
        
        try {
            $response = Http::timeout(5)->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (!$response->successful()) {
                $this->fallbackToFile($record);
            }
        } catch (\Exception $e) {
            $this->fallbackToFile($record);
        }
    }

    private function formatMessage(LogRecord $record): string
    {
        $level = strtoupper($record->level->name);
        $message = $record->message;
        $context = !empty($record->context) ? json_encode($record->context, JSON_UNESCAPED_UNICODE) : '';
        $extra = !empty($record->extra) ? json_encode($record->extra, JSON_UNESCAPED_UNICODE) : '';
        
        $text = "<b>[{$level}]</b> {$message}";
        
        if ($context) {
            $text .= "\n<b>Context:</b> {$context}";
        }
        
        if ($extra) {
            $text .= "\n<b>Extra:</b> {$extra}";
        }
        
        $text .= "\n<b>Time:</b> " . $record->datetime->format('Y-m-d H:i:s');
        
        return $text;
    }

    private function fallbackToFile(LogRecord $record): void
    {
        // Записываем в файл если Telegram недоступен
        Log::channel('single')->log(
            $record->level->name,
            '[TELEGRAM_FALLBACK] ' . $record->message,
            $record->context
        );
    }
}

