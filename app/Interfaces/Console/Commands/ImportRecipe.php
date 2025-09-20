<?php

namespace App\Interfaces\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory as HttpClient;

class ImportRecipe extends Command
{
    protected $signature = 'import_recipe {id}';
    protected $description = 'Импорт рецептов из ThemealDB по id';

    public function handle(
        HttpClient $http,
    ): int {
        $id = $this->argument('id');
        $url = "https://www.themealdb.com/api/json/v1/1/lookup.php?i={$id}";
        $response = $http->get($url);
        if ($response->failed()) {
            $this->error("Ошибка запроса: {$response->status()}");
            return Command::FAILURE;
        }
        if (is_null($response->json()['meals'])) {
            $this->error("Ошибка запроса: по id = {$id} отсутствует рецепт");
            return Command::FAILURE;
        }
        dd($response->json());
        return Command::SUCCESS;
    }
}