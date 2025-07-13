FROM php:8.3-cli

RUN apt-get update -yqq \
     && apt-get install -yqq unzip git libpq-dev libcurl4-gnutls-dev libicu-dev libvpx-dev libjpeg-dev libpng-dev libxpm-dev zlib1g-dev libfreetype6-dev libxml2-dev libexpat1-dev libbz2-dev libgmp3-dev libldap2-dev unixodbc-dev libsqlite3-dev libaspell-dev libsnmp-dev libpcre3-dev libtidy-dev libonig-dev libzip-dev \
     && docker-php-ext-install mbstring pdo_mysql curl gd xml zip \
     && pecl install xdebug \
     && docker-php-ext-enable xdebug \
     && curl -sS https://getcomposer.org/installer | php \
     && curl -fsSL https://bun.sh/install | bash
