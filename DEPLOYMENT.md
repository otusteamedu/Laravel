# Развёртывание приложения

## Настраиваем виртуальную машину

1. Подключаемся к ВМ и устанавливаем окружение командами (**команды для ubuntu 24.04**)
    ```shell
    sudo apt update
    sudo apt install curl git unzip mc
    ```
2. Устанавливаем зависимости для  docker (выполняем по одной команде за раз)
    ```shell
    sudo apt install ca-certificates
    sudo install -m 0755 -d /etc/apt/keyrings
    sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
    sudo chmod a+r /etc/apt/keyrings/docker.asc
   ```
   Это выполняется одной командой (скопировать-вставить всё сразу)
    ```shell
    sudo tee /etc/apt/sources.list.d/docker.sources <<EOF
    Types: deb
    URIs: https://download.docker.com/linux/ubuntu
    Suites: $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}")
    Components: stable
    Signed-By: /etc/apt/keyrings/docker.asc
    EOF
    ```
3. Устанавливаем сам docker и docker compose
    ```shell
    sudo apt update
    sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    ```
4. Проверяем, что всё установилось корректно и работает
    ```shell
    sudo systemctl status docker
    ```
5. Создаём директорию `/app` и меняем владельца на нашего пользователя ВМ
    ```shell
    sudo mkdir /app
    sudo chown 1000:1000 /app
    ```

## Устанавливаем и настраиваем Gitlab и Gitlab Runner

1. Cоздаём файл `/app/docker-compose.yml`
    ```yaml
    services:
       gitlab:
         image: gitlab/gitlab-ce:latest
         container_name: gitlab
         restart: always
         hostname: 'SERVER_URL_OR_HOST'
         environment:
           GITLAB_OMNIBUS_CONFIG: |
             external_url 'http://SERVER_URL_OR_HOST:7778'
             gitlab_rails['gitlab_shell_ssh_port'] = 9022
         ports:
           - '7778:7778'
           - '9022:22'
         volumes:
           - gitlab_config:/etc/gitlab
           - gitlab_logs:/var/log/gitlab
           - gitlab_data:/var/opt/gitlab
         shm_size: '256m'
    
    volumes:
       gitlab_config:
       gitlab_logs:
       gitlab_data:
    ```
   **Не забудьте заменить `SERVER_URL_OR_HOST` на актуальный адрес сервера/ВМ**
2. Запускаем контейнер командой `sudo docker compose up -d`
3. Ждём запуска Gitlab. Следить за ходом старта контейнера можно командой `sudo docker logs --follow gitlab`
4. Подключаемся к контейнеру Gitlab командой `sudo docker exec -it gitlab bash`
5. В контейнере запускаем консоль Rails командой `gitlab-rails console -e production`
6. В консоли ввести команды (`PASSWORD` – требуемый пароль):
    ```
    user = User.where(id: 1).first
    user.password = PASSWORD
    user.password_confirmation = PASSWORD
    user.save
    exit
    ```
7. Выходим из контейнера
8. Заходим в браузере по адресу `http://SERVER_URL_OR_HOST:7778`
    1. логинимся с логином `root` и указанным паролем
    2. Создаём группу и публичный репозиторий в ней
9. **В ВМ** выполняем команды по одной
    ```shell
    curl -s https://packages.gitlab.com/install/repositories/runner/gitlab-runner/script.deb.sh | sudo bash
    apt install -y gitlab-runner
    ```
   Актуальные команды можно посмотреть в Gitlab-репозитории в браузере, на вкладке `Settings -> CI/CD -> Runners`
10. В файл `/etc/sudoers` добавляем строку
     ```
     gitlab-runner ALL=(ALL) NOPASSWD:ALL
     ```
    Вариант запуска: `sudo visudo` и вносим те же правки
11. Удаляем файл `/home/gitlab-runner/.bash_logout` или комментируем его содержимое (**если создастся**)
12. На вкладке `Settings -> CI/CD -> Variables` добавляем переменную `DEPLOY_DIR` со значением `/app/deploy`
13. Выполняем команду регистрации из той же вкладки `Settings -> CI/CD -> Runners`, соглашаемся со всеми значениями по умолчанию, в качестве `executor` выбираем `shell`
    ```shell
    sudo gitlab-runner register --url http://SERVER_URL_OR_HOST:7778/ --registration-token <TOKEN>
    ```
14. В файл `/etc/gitlab-runner/config.toml` добавляем строку `shell = "bash"` после параметра `executor`
    ```
    shell = "bash"
    ```
15. Проверяем в интерфейсе, что runner появился

## Создаём директории для деплоев

1. Переходим в каталог `/app`
    ```shell
    cd /app
    ```
2. Создаём каталоги для релизов
    ```shell
    mkdir -p deploy/releases
    ```
3. Даём всем полные права на директории
    ```shell
    chmod a+rwx -R deploy
    ```
4. Переходим в директорию `releases`
    ```shell
    cd /app/deploy/releases
    ```
5. Создаём директории релизов и даём всем полные права
    ```shell
    mkdir shared
    mkdir blue
    mkdir green
    mkdir test
    chmod a+rwx shared
    chmod a+rwx blue
    chmod a+rwx green
    chmod a+rwx test
    ```
6. Копируем файл `.env` проекта в директорию `/app/deploy/releases/shared/`
   **Не забудьте поменять значение параметра `APP_URL` на адрес сервера/ВМ (`SERVER_URL_OR_HOST`)**
7. Меняем параметры в файле:
    ```shell
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=http://SERVER_URL_OR_HOST
    
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=prod
    DB_PASSWORD=prod
    ```
8. Создаём файл `.env.testing` - копию файла `.env` проекта в директории `/app/deploy/releases/shared/`
9. Меняем параметры в файле:
    ```shell
    APP_ENV=testing
    APP_URL=http://SERVER_URL_OR_HOST
    
    DB_HOST=test_mysql
    DB_PORT=3306
    DB_DATABASE=testing
    DB_USERNAME=testing
    DB_PASSWORD=testing
    ```
   
## Добавляем конфиг `haproxy`
1. Создаём директорию `/app/deploy/haproxy`
    ```shell
    mkdir /app/deploy/haproxy
    ```
2. Создаём файл `/app/deploy/haproxy/Dockerfile`
    ```dockerfile
    FROM haproxy:alpine
    USER root
    RUN apk add --no-cache socat
    RUN mkdir /sock && chown -R haproxy:haproxy /sock
    USER haproxy
    ```
3. Создаём файл `/app/deploy/haproxy/gateway.cfg`
    ```shell
    global
      stats socket /sock/admin.sock user haproxy group haproxy mode 660 level admin
      log stdout format raw local0 info
    
    defaults
      mode               http
      log                global
      timeout connect    5s
      timeout http-request 15s
      timeout queue      30s
      timeout client     300s
      timeout server     300s
    
    frontend  fe_main
      bind :80           # direct HTTP access
      default_backend    blue_green
    
    backend blue_green
      mode http
    
      option httpchk GET /up
      http-check expect status 200
    
      balance          sticky
      # green nginx
      server green	172.20.0.10:80 check inter 1s fall 2 rise 1
      # blue nginx
      server blue	172.20.0.11:80 check inter 1s fall 2 rise 1
    ```
   **В конце конфига нужна пустая строка. Здесь её нет, чтобы редактор нормально подсвечивал `yaml`-разметку**

## Добавляем `docker-compose.yml` для деплоев
1. Создаём файл `/app/deploy/docker-compose.yml`
    ```yaml
    services:
       gateway:
         build: haproxy
         container_name: gateway
         ports:
           - '80:80'
         volumes:
           - './haproxy/gateway.cfg:/usr/local/etc/haproxy/haproxy.cfg:r'
         networks:
           - main
    
       green_nginx:
         image: nginx:alpine
         container_name: greennginx
         volumes:
           - './nginx/green.conf:/etc/nginx/conf.d/default.conf:r'
           - './releases/green:/var/www/html'
           - './releases/shared/app_storage/public:/var/www/html/public/storage'
         networks:
           main:
             ipv4_address: 172.20.0.10
         depends_on:
           - green
         healthcheck:
           test: ["CMD", "curl", "-f", "http://localhost/"]
           interval: 5s
           timeout: 10s
           retries: 3
    
       blue_nginx:
         image: nginx:alpine
         container_name: bluenginx
         volumes:
           - './nginx/blue.conf:/etc/nginx/conf.d/default.conf:r'
           - './releases/blue:/var/www/html'
           - './releases/shared/app_storage/public:/var/www/html/public/storage'
         networks:
           main:
             ipv4_address: 172.20.0.11
         depends_on:
           - blue
         healthcheck:
           test: ["CMD", "curl", "-f", "http://localhost/"]
           interval: 5s
           timeout: 10s
           retries: 3
    
       blue:
         image: jkaninda/laravel-php-fpm:latest
         container_name: blue
         restart: unless-stopped
         user: www-data # For production
         environment:
           COLOR: blue
         volumes:
           - ./releases/blue:/var/www/html
           - ./releases/shared/app_storage:/var/www/html/storage/app
           - ./releases/shared/.env:/var/www/html/.env:r
         networks:
           main:
             ipv4_address: 172.20.0.20
    
       green:
         image: jkaninda/laravel-php-fpm:latest
         container_name: green
         restart: unless-stopped
         environment:
           COLOR: green
         user: www-data # For production
         volumes:
           - ./releases/green:/var/www/html
           - ./releases/shared/app_storage:/var/www/html/storage/app
           - ./releases/shared/.env:/var/www/html/.env:r
         networks:
           main:
             ipv4_address: 172.20.0.21
    
       test:
         image: jkaninda/laravel-php-fpm:latest
         container_name: test
         restart: unless-stopped
         depends_on:
           test_mysql:
             condition: service_healthy
         volumes:
           - ./releases/test:/var/www/html
           - ./releases/shared/.env.testing:/var/www/html/.env.testing:r
    
       test_mysql:
         image: mysql/mysql-server:8.0
         container_name: test_mysql
         environment:
           MYSQL_DATABASE: testing
           MYSQL_USER: testing
           MYSQL_PASSWORD: testing
           MYSQL_ROOT_PASSWORD: testing
         healthcheck:
           test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
           timeout: 20s
           retries: 10
    
       mysql:
         image: mysql/mysql-server:8.0
         container_name: mysql
         environment:
           MYSQL_DATABASE: laravel
           MYSQL_USER: prod
           MYSQL_PASSWORD: prod
           MYSQL_ROOT_PASSWORD: rootpassword
         volumes:
           - ./mysql:/var/lib/mysql
         networks:
           - main
    
    networks:
       main:
         driver: bridge
         ipam:
           config:
             - subnet: 172.20.0.0/24
    
    ```

## Добавляем скрипты деплоев

1. В директории `/app/deploy` создаём директорию `scripts` и даём всем полные права
    ```shell
    mkdir scripts
    chmod a+rwx scripts
    ```
2. Добавляем скрипт `/app/deploy/scripts/deploy.sh`
    ```shell
    #!/bin/bash
    
    cd /app/deploy/scripts
    
    if [ "$(sudo docker ps -q -f name=blue)" ]; then
        echo "Container 'blue' is running."
        CURRENT=green PREV=blue bash ./_deploy-general.sh
    else
        echo "Container 'blue' is not running."
        CURRENT=blue PREV=green bash ./_deploy-general.sh
    fi
    ```
3. Добавляем скрипт `/app/deploy/scripts/test.sh`
    ```shell
    #!/bin/bash
    
    function downloadNewCode {
        sudo rm -rf $DEPLOY_DIR/releases/test
        git clone http://gitlab-ci-token:${CI_JOB_TOKEN}@${CI_SERVER_FQDN}/${CI_PROJECT_PATH} $DEPLOY_DIR/releases/test
    }
    
    function buildApp {
        cd $DEPLOY_DIR
    
        sudo docker compose up -d test
        sudo docker exec --user root test composer install
    
        sudo docker exec --user root test php artisan migrate
    
        cd $DEPLOY_DIR/releases/test
    
        sudo /root/.bun/bin/bun install
        sudo /root/.bun/bin/bun run build
    }
    
    
    function runTests {
        sudo docker exec --user root test php artisan test
    }
    
    
    function stopTestsContainer {
        cd $DEPLOY_DIR
    
        sudo docker compose down test test_mysql --remove-orphans
    }
    
    
    downloadNewCode
    buildApp
    
    runTests
    stopTestsContainer
    ```
4. Добавляем скрипт `/app/deploy/scripts/_deploy-general.sh`
    ```shell
    #!/bin/bash
    
    if [ -z "$CURRENT" ]; then
        echo "Please set CURRENT variable"
        exit 1
    fi
    
    if [ -z "$PREV" ]; then
        echo "Please set PREV variable"
        exit 1
    fi
    
    function downloadNewCode {
        sudo rm -rf $DEPLOY_DIR/releases/$CURRENT
        git clone http://gitlab-ci-token:${CI_JOB_TOKEN}@${CI_SERVER_FQDN}/${CI_PROJECT_PATH} $DEPLOY_DIR/releases/$CURRENT
    }
    
    function buildApp {
        cd $DEPLOY_DIR
    
        sudo docker compose up -d $CURRENT
        sudo docker exec --user root $CURRENT composer install
        sudo docker exec --user root $CURRENT php artisan migrate
    
        cd $DEPLOY_DIR/releases/$CURRENT
    
        sudo /root/.bun/bin/bun install
        sudo /root/.bun/bin/bun run build
    }
    
    function optimizeResources {
        cd $DEPLOY_DIR
    
        sudo docker exec --user root $CURRENT php artisan optimize:clear
        sudo docker exec --user root $CURRENT php artisan optimize
    }
    
    function startCurrentRelease {
        cd $DEPLOY_DIR
    
        sudo docker compose up -d ${CURRENT}_nginx
        sleep 10
        sudo docker exec --user root gateway sh -c "echo \"set server blue_green/${CURRENT} state ready\" | socat stdio unix-connect:/sock/admin.sock"
    }
    
    function stopPrevRelease {
        cd $DEPLOY_DIR
    
        sudo docker exec --user root gateway sh -c "echo \"set server blue_green/${PREV} state maint\" | socat stdio unix-connect:/sock/admin.sock"
        sleep 10
        sudo docker compose down $PREV ${PREV}_nginx --remove-orphans
    }
    
    function changeOwnership {
        sudo chown www-data:www-data -R $DEPLOY_DIR/releases/$CURRENT
    }
    
    downloadNewCode
    buildApp
    optimizeResources
    changeOwnership
    
    startCurrentRelease
    stopPrevRelease
    ```

## Добавляем конфигурации `nginx` blue/green
1. В директории `/app/deploy` создаём директорию `nginx` и даём всем полные права
    ```shell
    mkdir nginx
    chmod a+rwx nginx
    ```
2. Создаём файл `/app/nginx/blue.conf`
    ```shell
    server {
        listen 80;
        index index.php;
        charset utf-8;
        error_log  /var/log/nginx/error.log;
        access_log /var/log/nginx/access.log;
        root /var/www/html/public;
        error_page 404 /index.php;
    
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
    
        location ~ ^/index\.php(/|$) {
                fastcgi_pass 172.20.0.20:9000;
    
                fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    
                include fastcgi_params;
    
                fastcgi_hide_header X-Powered-By;
    
        }
    }
    ```
3. Создаём файл `/app/nginx/green.conf`
    ```shell
    server {
        disable_symlinks off;
        listen 80;
        index index.php;
        charset utf-8;
   
        error_log  /var/log/nginx/error.log;
        access_log /var/log/nginx/access.log;
        root /var/www/html/public;
        error_page 404 /index.php;
    
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
    
        location ~ ^/index\.php(/|$) {
                fastcgi_pass 172.20.0.21:9000;
    
                fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    
                include fastcgi_params;
    
                fastcgi_hide_header X-Powered-By;
    
        }
    }
    ```
4. Создаём файл `.gitlab-ci.yml`
    ```yml    
    stages:
        - test
        - deploy
    
    tests:
        stage: test
        script:
            - cd $DEPLOY_DIR
            - bash ./scripts/test.sh
        only:
            - master
    
    deploy:
        stage: deploy
        script:
            - cd $DEPLOY_DIR
            - bash ./scripts/deploy.sh
        when: manual
    ```
5. Переходим в директорию `/app/deploy` и запускаем контейнеры `gateway` и `mysql`
    ```shell
    sudo docker compose up mysql gateway -d
    ```
6. Пушим код в ветку `master`. Видим в `Build -> Pipelines`, что пайплайн запустился. Пробуем запустить `deploy`

## Добавляем Rollback
1. Добавляем скрипт `/app/deploy/scripts/rollback.sh`
    ```shell
    #!/bin/bash
    
    cd $DEPLOY_DIR/scripts
    
    if [ "$(sudo docker ps -q -f name=green)" ]; then
        echo "Container 'green' is running."
        echo "Starting rollback"
        PREV=blue CURRENT=green bash ./_rollback-general.sh
    else
        echo "Container 'green' is not running."
        echo "Starting rollback"
        PREV=green CURRENT=blue bash ./_rollback-general.sh
    fi
    ```
2. Добавляем скрипт `/app/deploy/scripts/_rollback-general.sh`
    ```shell
    #!/bin/bash
    
    if [ -z "$CURRENT" ]; then
        echo "Please set CURRENT variable"
        exit 1
    fi
    
    if [ -z "$PREV" ]; then
        echo "Please set PREV variable"
        exit 1
    fi
    
    function startPrevRelease {
        cd $DEPLOY_DIR/releases
        sudo docker compose up -d $PREV ${PREV}_nginx
        sleep 10
        echo "set server blue_green/${PREV} state ready"
        sudo docker exec --user root gateway sh -c "socat stdio unix-connect:/sock/admin.sock"
    }
    
    function stopCurrentRelease {
        cd $DEPLOY_DIR/releases
        echo "set server blue_green/${CURRENT} state maint"
        sudo docker exec --user root gateway sh -c "socat stdio unix-connect:/sock/admin.sock"
        sleep 10
        sudo docker compose stop $CURRENT ${CURRENT}_nginx
    }
   
    startPrevRelease
    stopCurrentRelease
    ```
3. Создаём файл `.gitlab-ci.yml`
    ```yaml
    stages:
      - test
      - deploy
      - rollback
    
    tests:
      stage: test
      script:
        - cd $DEPLOY_DIR
        - bash ./scripts/test.sh
      only:
        - master
    
    deploy:
      stage: deploy
      script:
        - cd $DEPLOY_DIR
        - bash ./scripts/deploy.sh
      when: manual
    
    rollback:
      stage: rollback
      script:
        - cd $DEPLOY_DIR
        - bash ./scripts/rollback.sh
      when: manual
    ```
4. Пушим код в ветку `master`. Видим в `Build -> Pipelines`, что добавился пайплайн `rollback`. Пробуем запустить `rollback`

## Используем `Envoy`

1. Подключаемся к контейнеру Laravel в sail
    ```shell
    docker exec -it laravel-laravel.test-1 bash
    ```
2. Устанавливаем пакет
    ```shell
    composer require laravel/envoy --dev
    ```
3. Инициализируем
    ```shell
    ./vendor/bin/envoy init localhost
    ```
4. Исправляем файл `Envoy.blade.php` в корне проекта
    ```php
    @servers(['web' => 'localhost'])
    
    @task('check')
        echo "Hello from Envoyed script"
    @endtask
    ```
   
