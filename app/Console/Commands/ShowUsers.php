<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ShowUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:show-users
                            {userIds* : space separated userId}
                            {--H|headers=* : table headers}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userIds = $this->argument('userIds');
        $headers = $this->option('headers');

        if (empty($headers)) {
            $headers = ['id', 'email', 'name'];
        }

        $rows = User::whereIn('id', $userIds)->select($headers)->get();

        $this->table($headers, $rows);
    }
}
