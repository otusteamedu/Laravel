#!/bin/bash

set -euo pipefail  # Останавливаем при ошибках, undefined переменных и в пайпе

APP_DIR="/var/www/html"
SAIL="./vendor/bin/sail"

echo "Переходим в каталог проекта: $APP_DIR"
cd "$APP_DIR" || { echo "Ошибка: не удалось перейти в каталог $APP_DIR"; exit 1; }

echo "[STEP] Обновляем код из Git"
git fetch origin main
git reset --hard origin/main

echo "[STEP] Устанавливаем зависимости Composer с оптимизацией"
$SAIL composer install --no-dev --optimize-autoloader

echo "[STEP] Очищаем кеш"
$SAIL artisan optimize:clear

echo "[STEP] Запускаем тесты"
$SAIL artisan test || { echo "Ошибка: тесты не пройдены"; exit 1; }

echo "[STEP] Применяем миграции"
$SAIL artisan migrate --force

echo "[STEP] Сборка фронтенда"
npm install
npm run build

echo "[STEP] Оптимизация кеша"
$SAIL artisan optimize

echo "[STEP] Прогрев кеша"
$SAIL artisan cache:warm --force

echo "[STEP] Перезапуск очередей"
$SAIL artisan queue:restart

echo "[STEP] Перезапуск Docker Compose сервисов"
docker-compose up -d --remove-orphans

echo "Деплой успешно завершён"
