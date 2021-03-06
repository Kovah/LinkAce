# DOCKERFILE DEVELOPMENT
# Installs MySQL Client for database exports, xDebug with PCov and Composer

FROM php:7.3-fpm
WORKDIR /app

RUN apt-get update && apt-get install -y \
    zip \
    git \
    mariadb-client \
    autoconf \
    build-essential \
    libpq-dev \
    libzip-dev

RUN pecl install xdebug pcov
RUN docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip
RUN docker-php-ext-enable xdebug pcov

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 10000
