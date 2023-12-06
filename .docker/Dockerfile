# syntax=docker/dockerfile:1

FROM php:8.1.6-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y libxml2-dev \
    libzip-dev \
    libicu-dev \
    zlib1g-dev \
    linux-headers-generic\
    &&  rm -rf /tmp/pear \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug\
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc)  intl zip;

RUN apt-get update && apt-get install -y \
        libmcrypt-dev \
        libpq-dev \
        libxpm-dev \
        libvpx-dev \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql ;

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;

COPY --link php-docker-entrypoint.sh /usr/local/bin/php-docker-entrypoint
RUN chmod +x /usr/local/bin/php-docker-entrypoint

ENTRYPOINT ["php-docker-entrypoint"]
CMD ["php-fpm"]

ENV XDG_RUNTIME_DIR=/tmp/runtime-www-data