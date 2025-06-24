<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ChangeUserPasswordService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class ChangeUserPassword extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-user-password
                                {userId : user id for changing password}
                                {password? : raw password}
                                {--s|show-password : show raw password in output}
                                {--e|send-email : should we send email to user}
                                {--r|random-password : skip mode, use random password}
                                {--l|random-password-length=11 : random generated password length}
                                {--no-info : No result info }
                                ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change password for existing user';

    /**
     * Execute the console command.
     */
    public function handle(ChangeUserPasswordService $changeUserPasswordService)
    {
        // get input
        $userId = $this->argument('userId');
        $password = $this->argument('password');
        $showPassoword = $this->option('show-password');
        $sendEmail = $this->option('send-email');

        if (empty($password)) {
            $useRandomPassword = $this->option('random-password');
            $passwordLength = $this->option('random-password-length') ?? 11;

            if ($useRandomPassword) {
                $password = Str::password($passwordLength);
            } else {
                $mode = $this->choice("Password mode", ["input", "random"]);

                if ($mode === "input") {
                    $password = $this->secret("Enter password");
                } else {
                    $password = Str::password($passwordLength);
                }
            }
        }

        // act
        $user = User::findOrFail($userId);
        $changeUserPasswordService->changePassoword($user, $password);

        if ($sendEmail) {
            $this->info("Email is sent");
        }

        $printedPassword = ($showPassoword) ? $password : '<secret>';
        if (!$this->option('no-info')) {
            $this->info("Password ($printedPassword) for user (id=$userId, email={$user->email}) has been changed");
        }
    }
}
