@servers(['prod' => 'deployer@your.production.server'])  {{-- Указать сервер и пользователя --}}

@setup
$app_dir = '/var/www/html';  // Каталог проекта
$sail = './vendor/bin/sail';  // Путь к sail
@endsetup

@task('deploy', ['on' => 'prod'])
echo "Переход в каталог проекта"
cd {{ $app_dir }}

echo "[STEP] Обновляем код"
git fetch origin main
git reset --hard origin/main

echo "[STEP] Устанавливаем зависимости Composer"
{{ $sail }} composer install --no-dev --optimize-autoloader

echo "[STEP] Очищаем кеш"
{{ $sail }} artisan optimize:clear

echo "[STEP] Запускаем тесты"
{{ $sail }} artisan test || exit 1

echo "[STEP] Применяем миграции"
{{ $sail }} artisan migrate --force

echo "[STEP] Собираем фронтенд"
npm install
npm run build

echo "[STEP] Оптимизируем кеш"
{{ $sail }} artisan optimize

echo "[STEP] Прогреваем кеш"
{{ $sail }} artisan cache:warm --force

echo "[STEP] Перезапускаем очереди"
{{ $sail }} artisan queue:restart

echo "[STEP] Перезапускаем docker-compose контейнеры"
docker-compose up -d --remove-orphans

echo "Деплой успешно завершён"
@endtask

@task('rollback', ['on' => 'prod'])
echo "Выполняем откат миграции"
cd {{ $app_dir }}
{{ $sail }} artisan migrate:rollback --step=1
{{ $sail }} artisan cache:clear
echo "Откат завершён"
@endtask
