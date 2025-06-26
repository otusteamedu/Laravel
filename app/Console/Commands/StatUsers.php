<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UsersService;
use function Laravel\Prompts\table;
use function Laravel\Prompts\info;

class StatUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:users {--details}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show users statistics';

    /**
     * Execute the console command.
     */
    public function handle(
        UsersService $service,
    )
    {
        $details = $this->option('details');

        $count = $service->getCount();
        info('Total: ' . $count);

        if ($details) {
            $userCounts = $service->getAll()->pluck('role_id')->countBy()->all();
            $data = [[$userCounts['1'], $userCounts['2'], $userCounts['3']]];
            $headers = ['Administrator', 'Manager', 'User'];
            table($headers, $data);
        } 
    }
}
