<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetCountVacanciesFromHH extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-count-vacancies-from-h-h
                                                {companyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets count of actual vacancies of given company from headhunter.ru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->argument('companyId');

        $i = 0;
        $vacancies = [];

        do {
            $url = 'https://api.hh.ru/vacancies?employer_id='.$companyId.'&locale=RU&per_page=100&page='.$i;
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
            $vacancies = array_merge($vacancies, $res['items']);
            $i++;
            if (! empty($res['items']) && ! isset($companyName)) {
                $companyName = $res['items'][0]['employer']['name'];
            }
        } while (! empty($res['items']));

        if (empty($vacancies)) {
            $this->line('Не найдена компания по заданному id');

        } else {
            $this->line('Компания: '.$companyName);
            $this->line('Название вакансии | Город | Зарплата от-до');

            foreach ($vacancies as $item) {
                $line = '';
                if (isset($item['name'])) {
                    $line .= $item['name'].' | ';
                }
                if (isset($item['address']['city'])) {
                    $line .= $item['address']['city'].' | ';
                }
                if (isset($item['salary']['from'])) {
                    $line .= $item['salary']['from'].'-';
                }
                if (isset($item['salary']['to'])) {
                    $line .= $item['salary']['to'];
                }

                $this->line($line);
            }
        }

    }
}
