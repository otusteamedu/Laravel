#!/bin/bash
set -e

DEPLOY_DIR=$(dirname "$0")
cd "$DEPLOY_DIR/.."

# Определяем текущую и следующую версии
ACTIVE=$(cat deploy/active_version.txt)
if [ "$ACTIVE" = "blue" ]; then
  NEXT="green"
else
  NEXT="blue"
fi

echo "Current active version: $ACTIVE"
echo "Deploying next version: $NEXT"

# 1. Собираем Docker образ
echo "Building Docker image..."
docker build -t laravel_app:$NEXT -f docker/php/Dockerfile .

# 2. Поднимаем следующую версию
echo "Starting $NEXT version..."
docker-compose -f docker-compose.$NEXT.yml up -d --build

# 3. Прогоняем тесты
echo "Running tests on $NEXT version..."
if ! timeout 60s docker exec otus_laravel_app_$NEXT php artisan test; then
  echo "Tests failed or timed out! Rolling back..."
  docker-compose -f docker-compose.$NEXT.yml down
  exit 1
fi

# 4. Переключаем Nginx на новую версию
echo "Switching Nginx to $NEXT version..."
sed -i "s/server app-$ACTIVE:9000;/# server app-$ACTIVE:9000;/g" deploy/nginx.conf
sed -i "s/# server app-$NEXT:9000;/server app-$NEXT:9000;/g" deploy/nginx.conf
docker exec otus_nginx_server nginx -s reload

# 5. Обновляем активную версию
echo $NEXT > deploy/active_version.txt

# 6. Опционально, можно убрать старую версию
echo "Stopping old version $ACTIVE..."
docker-compose -f docker-compose.$ACTIVE.yml down

echo "✅ Deployment to $NEXT completed successfully!"
