@servers(['web' => 'localhost'])

@task('check')
    pwd
    echo "Hello from Envoyed script"
@endtask
