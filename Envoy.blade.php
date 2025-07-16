@servers(['localhost' => '127.0.0.1'])

@setup
    $repo = 'git@github.com:otusteamedu/Laravel.git';
    $appDir = '/var/www/elisad5791.fvds.ru';
    $branch = 'ABurnysheva/prod';
    $date = date('YmdHis');
    $builds = $appDir . '/releases';
    $deployment = $builds . '/' . $date;
    $serve = $appDir . '/current';
    $env = $appDir . '/.env';
    $storage = $appDir . '/storage';
@endsetup

@story('deploy')
    git
    install
    test
    live
@endstory

@task('git')
    echo "Cloning repository..."
    git clone -b {{ $branch }} "{{ $repo }}" {{ $deployment }}
@endtask

@task('install')
    cd {{ $deployment }}
    rm -rf {{ $deployment }}/storage
    ln -nfs {{ $env }} {{ $deployment }}/.env
    ln -nfs {{ $storage }} {{ $deployment }}/storage
    echo "Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
    echo "Installing npm dependencies..."
    npm i
    echo "Running migrations..."
    php artisan migrate --force
    php artisan storage:link
@endtask

@task('test')
    cd {{ $deployment }}
    echo "Running tests..."
    php artisan test
    if [ $? -ne 0 ]; then
        echo "Tests failed! Deployment aborted."
        exit 1
    fi
@endtask

@task('live')
    echo "Updating symlink..."
    ln -nfs {{ $deployment }} {{ $serve }}
@endtask