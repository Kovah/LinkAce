# DOCKERFILE DEVELOPMENT
# Installs database clients for database exports, xDebug with PCov and Composer

FROM docker.io/library/php:8.5-fpm-alpine
WORKDIR /app

# Install package and PHP dependencies
# $PHPIZE_DEPS provides the toolchain (gcc, make, autoconf, ...) needed to
# compile PHP extensions. It is removed again once the extensions are built.
RUN apk add --no-cache zip git mariadb-client postgresql-client sqlite zip libzip \
  && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers libzip-dev postgresql-dev \
  && pecl install xdebug pcov \
	&& docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp sockets \
  && docker-php-ext-enable xdebug pcov \
	&& mkdir /ssl-certs \
	&& docker-php-source delete \
	&& rm -f /usr/src/php.tar.xz /usr/src/php.tar.xz.asc \
	&& rm -rf /tmp/pear \
	&& apk del --no-cache .build-deps

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 10000
