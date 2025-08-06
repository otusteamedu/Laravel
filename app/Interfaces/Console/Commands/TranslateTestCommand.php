<?php

namespace App\Interfaces\Console\Commands;

use Illuminate\Console\Command;
use Stronger21012\Autotranslator\Services\TranslatorInterface;

class TranslateTestCommand extends Command
{
    protected $signature = 'autotranslate:test 
                            {text="Hello world"} 
                            {--from=en} 
                            {--to=ru}';

    protected $description = 'Тестирует автоперевод через AutoTranslator';
    
    protected TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
        parent::__construct();
    }

    public function handle(): int
    {
        $text = $this->argument('text');
        $from = $this->option('from');
        $to = $this->option('to');

        try {
            $translated = $this->translator->translate($text, $from, $to);

            $this->info("Оригинал: {$text}");
            $this->info("Перевод: {$translated}");
        } catch (\Throwable $e) {
            $this->error("Ошибка перевода: " . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
