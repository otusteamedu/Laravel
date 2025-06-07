FROM ubuntu:24.04 as app

ARG WWWUSER
ARG WWWGROUP
ARG NODE_VERSION=22
ARG POSTGRES_VERSION=17

WORKDIR /var/www/html

ENV TZ=MSK
ENV SUPERVISOR_PHP_USER="sail"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get -y install sudo git libpq-dev ca-certificates ghostscript curl gnupg \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y \
    php8.4-cli \
    php8.4-dev \
    php8.4-fpm \
    php8.4-curl \
    php8.4-memcached \
    php8.4-memcache \
    php8.4-mbstring \
    php8.4-common\
    php8.4-mysql \
    php8.4-intl \
    php8.4-xdebug \
    php8.4-interbase \
    php8.4-soap \
    php8.4-gd \
    php8.4-imagick \
    php8.4-redis \
    php8.4-opcache \
    php8.4-zip \
    php8.4-dom \
    php8.4-xml \
    php-pear libmcrypt-dev gcc make autoconf libc-dev pkg-config \
    && apt-get install -y libsodium-dev \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt noble-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ADD ./php/russian_trusted_root_ca_pem.crt /usr/local/share/ca-certificates/russian_trusted_root_ca_pem.crt
ADD ./php/russian_trusted_sub_ca_pem.crt /usr/local/share/ca-certificates/russian_trusted_sub_ca_pem.crt

RUN update-ca-certificates -v

RUN userdel -r ubuntu

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER sail

ADD ./php/php.ini /etc/php/8.4/fpm/conf.d/90-php.ini
ADD ./php/www.conf /etc/php/8.4/fpm/pool.d/www.conf
ADD ./php/php.ini /etc/php/8.4/cli/conf.d/90-php.ini

COPY ./*.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/*.sh

EXPOSE 9000/tcp

CMD /usr/local/bin/docker_entrypoint.sh
