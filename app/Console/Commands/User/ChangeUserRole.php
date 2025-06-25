<?php

namespace App\Console\Commands\User;

use App\Services\User\ChangeUserRoleService;
use Illuminate\Console\Command;

class ChangeUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-user:change-user-role
                            {email? : The email of the user to change role}
                            {roleId? : The ID of the role to update?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes user role';

    /**
     * Execute the console command.
     */
    public function handle(ChangeUserRoleService $changeUserRoleService): void
    {
        $roleName = $changeUserRoleService->handle(
            $this->argument('email') ?? $this->ask('Please enter user email', ''),
            $this->argument('roleId') ?? (int) $this->ask('Enter role ID: 1 for User, 2 for Admin'),
        );

        $this->info("Установлена роль: $roleName");
    }
}
