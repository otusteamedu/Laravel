<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\Common\FetchOptions;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\UseCases\Commands\Auth\Password\Reset\Handler;
use \App\Services\UseCases\Commands\Auth\Password\Reset\Command as PasswordReset;
use Illuminate\Support\Facades\Password;


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
     * Summary of __construct
     * @param UserRepositoryInterface $userRepository
     * @param Handler $handler
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Handler $handler,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return int
     * @throws \Throwable
     */
    public function handle(): int
    {
        if (!$users = $this->getUsers()) {
            $users = $this->requestUsers();
        }
        $this->info('Начинаем процесс сброса паролей');

        $this->trap([SIGTERM, SIGQUIT], fn() => $this->shouldKeepRunning = false);

        $this->withProgressBar($users, function (UserDTO $user) {
            if (!$this->shouldKeepRunning) {
                $this->fail('Получен сигнал на завершение работы');
            }

            $this->resetPassword($user);
        });

        $this->line(PHP_EOL);
        $this->info('Сброс паролей завершен');

        if ($this->fails > 0) {
            $this->error(
                sprintf(
                    ' Не удалсь сбросить <options=bold><bg=red>%d</></> паролей из <options=bold><bg=red>%d</></> ',
                    $this->fails,
                    count($users)
                )
            );
        }

        return self::SUCCESS;
    }

    /**
     * Получение массива данных пользователей, которым нужно сбросить пароли
     *
     * @return UserDTO[]|null
     */
    private function getUsers(): array|null
    {
        if ($this->option('all')) {
            $users = $this->userRepository->fetch(new FetchOptions());

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
                $users = $this->userRepository->fetch(new FetchOptions(ids: $userIds));
            }
        }

        return $users ?? null;
    }

    /**
     * Получение массива данных пользователей в интерактивном режиме
     *
     * @return UserDTO[]
     * @throws \Throwable
     */
    private function requestUsers(): array
    {
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


            if (empty($userIds)) {
                $this->fail('Введены недопустимые для Id значения');
            }

            $users = $this->userRepository->fetch(new FetchOptions(ids: $userIds));
        } else {
            $users = $this->userRepository->fetch(new FetchOptions());
        }

        return $users;
    }

    /**
     * Сброс паролья пользователю
     * 
     * @param UserDTO $user
     * @return void
     */
    private function resetPassword(UserDTO $user): void
    {
        $status = $this->handler->handle(new PasswordReset(
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
    }
}
