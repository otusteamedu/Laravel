<?php

namespace App\Interfaces\Console\Commands;

use App\Application\Actions\Translation\TranslateActionInterface;
use Illuminate\Console\Command;

class TranslateTestCommand extends Command
{
    protected $signature = 'autotranslate:test 
                            {text="Hello world"} 
                            {--from=en} 
                            {--to=ru}';

    protected $description = 'Тестирует автоперевод через AutoTranslator';

    protected TranslateActionInterface $translator;

    public function __construct(
        TranslateActionInterface $translator
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
            $translation = $this->translator->translate($from, $to, $text);
            
            $this->info("Оригинал: {$text}");
            $this->info("Перевод: {$translation['textTo']}");
        } catch (\Throwable $e) {
            $this->error("Ошибка перевода: " . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
