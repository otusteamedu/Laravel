FROM php:8.3-fpm

# Установим зависимости
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql xml

RUN apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# Установка Node и npm (для работы на front)
# RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
#     && apt-get install -y nodejs

# Установка Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Настройка Xdebug для покрытия
RUN mv /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini.disabled
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Установка прав на phpunit (на всякий случай)
RUN chmod +x /var/www/vendor/bin/phpunit || true

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www

# Копируем файлы проекта
COPY . .

# Установка зависимостей Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
