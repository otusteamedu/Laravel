<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:bulk-password-update {userIds* : User ids}')]
#[Description('Bulk user password update')]
class BulkPasswordUpdate extends Command
{
    protected $shouldKeepRunning = true;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->trap([SIGHUP], fn() => $this->shouldKeepRunning = false);

        $userIds = $this->argument('userIds');

        $this->withProgressBar($userIds, function ($userId) {
            if (!$this->shouldKeepRunning) {
                $this->fail('Stopped by SIGHUP');
            }

            sleep(10);

            $this->callSilently(UpdateUserPassword::class, [
                'userId' => $userId,
                '--generate-password' => true,
                '--password-length' => 10,
                '--send-email' => true,
                '--show-password' => true,
            ]);
        });

        $this->line('');
    }
}
