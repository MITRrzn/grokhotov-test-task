FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        libicu-dev \
        libzip-dev \
        libpq-dev \
        libc-client-dev\
        libkrb5-dev\
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip\
    && docker-php-ext-install opcache pdo pdo_mysql \
    && usermod -u 1000 www-data

EXPOSE 9000
WORKDIR /srv