@servers(['web' => 'localhost'])

@task('optimize')
    php artisan optimize:clear
    php artisan optimize
@endtask
