<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:show-users {userIds* : User ids} {--H|header=* : table header}')]
#[Description('Show users in table')]
class ShowUsers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userIds = $this->argument('userIds');
        $headers = $this->option('header');

        if (empty($headers)) {
            $headers = ['id', 'email'];
        }

        $rows = User::whereIn('id', $userIds)->select($headers)->get();

        $this->table($headers, $rows);
    }
}
