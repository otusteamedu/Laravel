<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
class SendTelegram extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-telegram
    {message : text message} {--d=debug}
    {--d|default-channel : default channel Topic }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправление уведомления в Телеграм';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = now();
        $message = $this->argument('message');
      //  $channel = $this->argument('channel');
        if(!$this->option('default-channel')){
            $channel = 'debug'; 
        }
        else{
          $channel = $this->option('default-channel');  
        }
        \ProgTime\TgLogger\TgLogger::sendLog($message,$channel);
        $time = $start->diffInSeconds(now());
        $this->info("Отправление заняло ".$time." секунд");
        $this->info("Уведомление успешно отправлено");
    }
}
