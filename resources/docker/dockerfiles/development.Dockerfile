# DOCKERFILE DEVELOPMENT
# Installs database clients for database exports, xDebug with PCov and Composer

FROM docker.io/library/php:8.2-fpm-alpine
WORKDIR /app

# Install package and PHP dependencies
RUN apk add --no-cache zip git mariadb-client postgresql-client postgresql-dev sqlite zip libzip-dev linux-headers autoconf make \
  && pecl install xdebug pcov \
	&& docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp sockets \
  && docker-php-ext-enable xdebug pcov \
	&& mkdir /ssl-certs \
	&& docker-php-source delete \
	&& rm -f /usr/src/php.tar.xz /usr/src/php.tar.xz.asc \
	&& apk del --no-cache postgresql-dev autoconf make

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 10000
