
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
    build-app
    update-permissions
    optimize-resource
    switch-releases
@endmacro

@macro('rollback',['on'=>'web'])
    rollback-app
    optimize-resource
    switch-releases
@endmacro

@task('set-releases')
    CURRENT=$(sudo docker exec -t otus-app-balance sh -c "echo \"show stat up\" | socat stdio unix-connect:/sock/admin.sock | grep -m 1 -oE 'blue_green\,([a-z]+)' | cut -f2 -d, | tr -d '\n'")

    if [ "$CURRENT" == "blue" ]
    then
        PREVIOUS="green"
    else
        PREVIOUS="blue"
    fi

    cd {{ $app_dir }};

    echo "CURRENT=$CURRENT" > .releases
    echo "PREVIOUS=$PREVIOUS" >> .releases
@endtask

@task('fetch-repo')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)
    
    cd {{ $releases_dir }}/${PREVIOUS};

    git fetch origin {{ $baseDir }}
    git checkout {{ $branch }}
    git reset --hard origin/{{ $branch }}

    echo "Репозиторий для релиза ${PREVIOUS} обновлен"
@endtask

@task('build-app')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    echo "Ставим зависимости composer otus-app-${PREVIOUS}-app"

    sudo docker exec -t otus-app-${PREVIOUS}-app composer install --prefer-dist --no-interaction
    echo "Зависимости composer для релиза ${PREVIOUS} установлены"

    sudo docker exec -t otus-app-${PREVIOUS}-app php artisan migrate --force --no-interaction
    echo "Миграции для релиза ${PREVIOUS} применены"

    sudo docker exec -t otus-app-${PREVIOUS}-app npm install
    echo "Зависимости node для релиза ${PREVIOUS} установлены"

    sudo docker exec -t otus-app-${PREVIOUS}-app npm run build
    echo "Ассеты для релиза ${PREVIOUS} собраны"

    echo "Сборка проекта для релиза ${PREVIOUS} завершена"
@endtask

@task('update-permissions')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    sudo chmod -R ug+rw {{ $releases_dir }}/${PREVIOUS}/storage
    sudo chgrp -R www-data {{ $releases_dir }}/${PREVIOUS}/storage
    echo "Права доступа к {{ $releases_dir }}/${PREVIOUS}/storage установлены"
@endtask

@task('rollback-app')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    docker exec -t otus-app-${PREVIOUS}-app php artisan migrate:rollback --force
    echo "Миграции для релиза ${CURRENT} отменены"

    echo "Откат к релизу ${PREVIOUS} завершен"
@endtask

@task('optimize-resource')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    docker exec -t otus-app-${PREVIOUS}-app php artisan optimize:clear
    docker exec -t otus-app-${PREVIOUS}-app php artisan optimize

    echo "Ресурсы для релиза ${PREVIOUS} оптимизированы"
@endtask

@task('switch-releases')
    export $(grep -v '^#' {{ $app_dir }}/.releases | xargs)

    docker exec -t otus-app-balance sh -c "echo \"set server blue_green/${PREVIOUS} state ready\" | socat stdio unix-connect:/sock/admin.sock"
    echo "Текущий релиза переключен на ${PREVIOUS}"
    
    sleep 10
    
    docker exec -t otus-app-balance sh -c "echo \"set server blue_green/${CURRENT} state maint\" | socat stdio unix-connect:/sock/admin.sock"
    echo "Релиз ${CURRENT} переведен в статус MAINTENANCE"
@endtask

@finished
    @telegram(env('TELEGRAM_API_KEY'), env('TELEGRAM_CHAT_ID'))
@endfinished
