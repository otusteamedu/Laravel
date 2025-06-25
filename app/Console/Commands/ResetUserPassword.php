<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\Common\FetchOptions;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\UseCases\Commands\Auth\Password\Reset\Handler;
use \App\Services\UseCases\Commands\Auth\Password\Reset\Command as PasswordReset;
use Illuminate\Support\Facades\Password;

use function Symfony\Component\String\s;

class ResetUserPassword extends Command
{
    /**
     * Количество ошибок в процессе сброса паролей пользователей
     * @var int
     */
    protected int $fails = 0;

    /**
     * Флаг прерывания выполнения команды
     * @var bool
     */
    protected bool $shouldKeepRunning = true;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password
                            {userIds?* : Id пользователей или пустой для всех}
                            {--a|all : Сбросить пароль всем пользователям игнорируя содержимое аргумена userIds}
                            {--s|send-email : Отправляь пользователю на email ссылку на форму смены пароля}
                            {--f|force-reset : Сбрросить текущий пароль пользователя}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Массовый сброс паролей пользователей';

    /**
     * Execute the console command.
     */
    public function handle(Handler $handler, UserRepositoryInterface $userRepository)
    {
        $this->trap([SIGTERM, SIGQUIT], fn() => $this->shouldKeepRunning = false);

        if ($this->option('all')) {
            $users = $userRepository->fetch(new FetchOptions);

            if (!$users) {
                $this->fail('Не удалось найти ни одного пользователя');
            }
        } else {
            $userIds = array_map(
                fn($userId) => intval($userId),
                $this->argument('userIds')
            );

            $userIds = array_filter(
                array_unique($userIds)
            );

            if (!empty($userId)) {
                $users = $userRepository->fetch(new FetchOptions(ids: $userIds));
            }
        }

        if (empty($users)) {
            $this->warn('Вы не указали ни оного пользователя или указали недопустимые для Id значения');
            $choise = $this->choice(
                'Ввести Id пользователей?',
                ['Да', 'Нет, сбросить для всех'],
                0
            );

            if ($choise === 'Да') {
                $input = $this->ask('Введите Id пользователей через пробел');

                $userIds = array_map(
                    fn($userId) => intval($userId),
                    explode(" ", $input)
                );

                $userIds = array_filter(
                    array_unique($userIds)
                );
            }

            if (empty($userIds)) {
                $this->fail('Введены недопустимые для Id значения');
            }

            $users = $userRepository->fetch(new FetchOptions(ids: $userIds));
        }

        $this->info('Начинаем процесс сброса паролей');
        $this->withProgressBar($users, function (UserDTO $user) use ($handler) {

            if (!$this->shouldKeepRunning) {
                $this->fail('Получен сигнал на завершение работы');
            }

            $status = $handler->handle(new PasswordReset(
                email: $user->email,
                sendResetLink: $this->option('send-email') ?? true,
                forceReset: $this->option('force-reset') ?? false
            ));

            if (
                $status->routeName !== Password::RESET_LINK_SENT
                && $status->routeName !== Password::PASSWORD_RESET
            ) {
                $this->error(' Не смогли сбросить пароль для пользователя ' . $user->email . ': ' . $status->routeName . ' ');
                $this->fails++;
            }
        });

        $this->line(PHP_EOL);

        $this->info('Сброс паролей завершен');

        if ($this->fails > 0) {
            $this->error(sprintf(' Не удалсь сбросить <options=bold><bg=red>%d</></> паролей из <options=bold><bg=red>%d</></> ', $this->fails, count($users)));
        }

        return self::SUCCESS;
    }
}
