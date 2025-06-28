<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetLargestEmployers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-largest-employers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets companies from headhunter with count of vacancies more than 300 from first 1000';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Название компании | ID | Количество вакансий');

        for ($i = 0; $i < 1000; $i++) {
            $url = 'https://api.hh.ru/employers/'.$i;
            $options = [
                CURLOPT_RETURNTRANSFER => true,   // return web page
                CURLOPT_HEADER => false,  // don't return headers
                CURLOPT_FOLLOWLOCATION => true,   // follow redirects
                CURLOPT_ENCODING => '',     // handle compressed
                CURLOPT_USERAGENT => 'test', // name of client
                CURLOPT_AUTOREFERER => true,   // set referrer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
                CURLOPT_TIMEOUT => 120,    // time-out on response
            ];
            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($response, true);

            if (empty($res['errors']) && $res['open_vacancies'] > 300) {
                $this->line($res['name'].' '.$res['id'].' '.$res['open_vacancies']);
            }
        }

        $this->line('Done!');

    }
}
