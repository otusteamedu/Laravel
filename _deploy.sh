#!/bin/bash

set -e

PROJECT_ROOT="/home/serg/LaravelDz"
RELEASES_DIR="$PROJECT_ROOT/releases"
SHARED_DIR="$PROJECT_ROOT/shared_folders"
CURRENT_TIMESTAMP=$(date +%Y-%m-%d-%H%M%S)
NEW_RELEASE_DIR="$RELEASES_DIR/$CURRENT_TIMESTAMP"
GIT_REPO="https://github.com/otusteamedu/Laravel.git"
GIT_BRANCH="SCherepanov/hw15"

echo "=== Deploy started at $CURRENT_TIMESTAMP ==="

# Создаем нужные директории, если их нет
mkdir -p "$RELEASES_DIR"
mkdir -p "$SHARED_DIR"

echo "Cloning repo branch $GIT_BRANCH into $NEW_RELEASE_DIR"
git clone --branch "$GIT_BRANCH" --depth 1 "$GIT_REPO" "$NEW_RELEASE_DIR"

# Копируем .env из shared_folders в релиз
if [ -f "$SHARED_DIR/.env" ]; then
    echo "Copying .env from shared folder"
    cp "$SHARED_DIR/.env" "$NEW_RELEASE_DIR/.env"
else
    echo "Warning: .env file not found in shared folder!"
fi

cd "$NEW_RELEASE_DIR"

echo "Installing composer dependencies"
composer install --no-interaction --prefer-dist --optimize-autoloader

# Генерируем ключ приложения, если нужно
if ! grep -q 'APP_KEY=' .env || [ "$(grep 'APP_KEY=' .env | cut -d '=' -f2)" == "" ]; then
    echo "Generating application key"
    ./vendor/bin/sail artisan key:generate
else
    echo "APP_KEY already set, skipping key generation"
fi

./vendor/bin/sail up -d


echo "Waiting for PostgreSQL"
until ./vendor/bin/sail exec pgsql pg_isready -U postgres > /dev/null 2>&1; do
  echo -n "."
  sleep 2
done

echo "PostgreSQL is ready!"

echo "Running main migrations"
./vendor/bin/sail artisan migrate --force

echo "Running database seeders"
./vendor/bin/sail artisan db:seed --force

echo "Clearing and caching config, routes, views"
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache

echo "Updating 'current' symlink"
ln -sfn "$NEW_RELEASE_DIR" "$PROJECT_ROOT/current"

echo "Cleaning up old releases"
cd "$RELEASES_DIR"
ls -1dt */ | tail -n +4 | xargs -r rm -rf

echo "=== Deploy finished ==="
