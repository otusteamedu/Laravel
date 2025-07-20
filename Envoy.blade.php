
@servers(['web' => ['vhar@qrx.local']])

@setup
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $branch       = 'VHarinenkov/main';
    $app_dir      = '/var/www/otus-app';
    $releases_dir =  $app_dir . '/releases';
@endsetup

@macro('deploy',['on'=>'web'])
    set-releases
    fetch-repo
    run-composer
    update-permissions
    assets-install
{{-- тесты web, не придумал как их запускать не переключив релиза --}}
{{-- testing --}}
    migrate
    optimize-resource
    switch-releases
@endmacro

@macro('rollback',['on'=>'web'])
    set-releases
    rollback-migrate
    optimize-resource
    switch-releases
@endmacro

@task('set-releases')
    CURRENT=$(sudo docker exec -t otus-app-balance sh -c "echo \"show stat up\" | socat stdio unix-connect:/sock/admin.sock | grep -m 1 -oE 'blue_green\,([a-z]+)' | cut -f2 -d, | tr -d '\n'")

    if [ "$CURRENT" == "blue" ]
    then
        NEXT="green"
    else
        NEXT="blue"
    fi

    cd {{ $app_dir }};

    echo "CURRENT=$CURRENT" > .releases
    echo "NEXT=$NEXT" >> .releases
@endtask

@task('fetch-repo')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)
    
    cd {{ $releases_dir }}/${NEXT};

    git fetch origin {{ $baseDir }}
    git checkout {{ $branch }}
    git reset --hard origin/{{ $branch }}

    echo "Репозиторий для релиза ${NEXT} обновлен"
@endtask

@task('run-composer')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    echo "Ставим зависимости composer otus-app-${NEXT}-app"

    sudo docker exec -t otus-app-${NEXT}-app composer install --prefer-dist --no-interaction
    echo "Зависимости composer для релиза ${NEXT} установлены"
@endtask

@task('update-permissions')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    sudo chmod -R ug+rw {{ $releases_dir }}/${NEXT}/storage
    sudo chgrp -R www-data {{ $releases_dir }}/${NEXT}/storage
    echo "Права доступа к {{ $releases_dir }}/${NEXT}/storage установлены"
@endtask

@task('assets-install')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    sudo docker exec -t otus-app-${NEXT}-app npm install
    echo "Зависимости node для релиза ${NEXT} установлены"

    sudo docker exec -t otus-app-${NEXT}-app npm run build
    echo "Ассеты для релиза ${NEXT} собраны"

    echo "Сборка проекта для релиза ${NEXT} завершена"
@endtask

@task('migrate')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    cd {{ $app_dir }};

    NEXT_MIGRATION=$(sudo docker exec -t otus-app-${NEXT}-app php artisan migrate:last | grep -oP 'id:\K\d+' | tr -d '\n')

    sudo docker exec -t otus-app-${NEXT}-app php artisan migrate --force --no-interaction

    LAST_MIGRATION=$(sudo docker exec -t otus-app-${NEXT}-app php artisan migrate:last | grep -oP 'id:\K\d+' | tr -d '\n')

    if [ $NEXT_MIGRATION = $LAST_MIGRATION ]
    then
        sudo rm -rf .migration
        echo "Нет миграций для релиза ${NEXT}"
    else
        echo "$LAST_MIGRATION" > .migration
        echo "Миграции для релиза ${NEXT} применены"
    fi
@endtask

@task('test')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    cd {{ $app_dir }};

    sudo docker exec -t otus-app-${NEXT}-app php artisan optimize:clear
    sudo docker exec -t otus-app-${NEXT}-app php artisan migrate --force --no-interaction --env=testing
    sudo docker exec -t otus-app-${NEXT}-app php artisan test

    echo "Тестирование релиза ${CURRENT} завершено"
@endtask

@task('rollback-migrate')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    cd {{ $app_dir }};

    if [ -f {{ $app_dir }}/.migration ]
    then
        sudo docker exec -t otus-app-${NEXT}-app php artisan migrate:rollback --force
        sudo rm -rf  {{ $app_dir }}/.mgration

        echo "Миграции для релиза ${CURRENT} отменены"
    else
        echo "Нет миграций требующих отката"
    fi
@endtask

@task('optimize-resource')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    sudo docker exec -t otus-app-${NEXT}-app php artisan optimize:clear
    sudo docker exec -t otus-app-${NEXT}-app php artisan optimize

    echo "Ресурсы для релиза ${NEXT} оптимизированы"
@endtask

@task('switch-releases')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    cd {{ $app_dir }};

    sudo docker compose start ${NEXT}_nginx
    echo "Запущен nginx для релиза ${NEXT}"

    sleep 10

    sudo docker exec -t otus-app-balance sh -c "echo \"set server blue_green/${NEXT} state ready\" | socat stdio unix-connect:/sock/admin.sock"
    echo "Текущий релиза переключен на ${NEXT}"
    
    sleep 10
    
    sudo docker exec -t otus-app-balance sh -c "echo \"set server blue_green/${CURRENT} state maint\" | socat stdio unix-connect:/sock/admin.sock"
    echo "Релиз ${CURRENT} переведен в статус MAINTENANCE"

    sleep 10

    sudo docker compose stop ${CURRENT}_nginx
    echo "Остановлен nginx для релиза ${CURRENT}"

@endtask

@error
    echo "Деплой завершился ошибкой в задаче $task" . PHP_EOL;
@enderror

@success
    echo "Деплой релиза завершен успешно" . PHP_EOL;
@endsuccess

@finished
    @telegram(env('TELEGRAM_API_KEY'), env('TELEGRAM_CHAT_ID'))
@endfinished
