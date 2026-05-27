<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UpdateUserPasswordService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;


#[Signature("app:update-user-password
                            {userId : User id}
                            {password? : New password}
                            {--r|generate-password : Don't ask password, just generate random one }
                            {--L|password-length=" . UpdateUserPassword::PASSWORD_LENGTH . " : Generated password length }
                            {--s|show-password : Print password in output}
                            {--e|send-email : Send email to user}
                        ")]
#[Description('Update password for given user')]
class UpdateUserPassword extends Command
{
    const PASSWORD_LENGTH = 3;

    /**
     * Execute the console command.
     */
    public function handle(UpdateUserPasswordService $service)
    {
        $userId = $this->argument('userId');
        $password = $this->argument('password');

        $showPassword = $this->option('show-password');
        $sendEmail = $this->option('send-email');

        $password = $this->getPassword();

        $user = User::findOrFail($userId);

        $service->updatePassword($user, $password);

        $passwordToShow = ($showPassword) ? $password : '<secret>';

        $this->info("Password ($passwordToShow) for user (id={$user->id}, email={$user->email}) has been updated");

        if ($sendEmail) {
            $this->info("Email is sent to {$user->email}");
        }
    }

    protected function getPassword()
    {
        $generatePassword = $this->option('generate-password');
        $passwordLength = $this->option('password-length') ? (int) $this->option('password-length') : 10;
        $noInteraction = $this->option('no-interaction');

        $password = null;

        if ($generatePassword || $noInteraction) {
            $password = Str::password($passwordLength);
        } else if (empty($password)) {
            $mode = $this->choice("How to fill password", ['manually', 'random']);

            if ($mode === 'manually') {
                $password = $this->secret('New password');
            } else {
                $password = Str::password($passwordLength);
            }
        }

        return $password;
    }
}
