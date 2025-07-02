<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GetVacancies implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $pageNumber,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = 'https://api.hh.ru/employers/'.$this->pageNumber;

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

        if ($response) {
            $res = json_decode($response, true);

            if (empty($res['errors']) && $res['open_vacancies'] > 300) {
                Storage::append('companies.txt', 'Задача №'.$this->pageNumber.', '.$res['name'].' '.$res['open_vacancies'].PHP_EOL, FILE_APPEND);
            }
        }

    }
}
