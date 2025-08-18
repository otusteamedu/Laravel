<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BulkUserPasswordUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bulk-user-password-update {password} {userIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes password for multiple users';

    protected $shouldKeepRunning = true;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->argument('password');
        $userIds = $this->argument('userIds');

        $this->trap([SIGTERM, SIGINT], fn() => $this->shouldKeepRunning = false);

        $this->withProgressBar($userIds, function ($userId) use ($password) {
            if (!$this->shouldKeepRunning) {
                $this->fail("Got signal to finish");
            }

            sleep(10);

            $this->call(ChangeUserPassword::class, [
                "--no-info" => true,
                "userId" => $userId,
                "password" => $password,
            ]);
        });

        // $progressBar = $this->output->createProgressBar(count($userIds));

        // $progressBar->start();

        // foreach ($userIds as $userId) {
        //     $this->call(ChangeUserPassword::class, [
        //         // "--no-info" => true,
        //         "userId" => $userId,
        //         "password" => $password,
        //     ]);
        //     $progressBar->advance();
        // }

        // $progressBar->finish();
        $this->newLine();
        $this->line('Done!');
    }
}
