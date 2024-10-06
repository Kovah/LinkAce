# DOCKERFILE DEVELOPMENT
# Installs database clients for database exports, xDebug with PCov and Composer

FROM docker.io/library/php:8.1-fpm-alpine
WORKDIR /app

# Install package and PHP dependencies
RUN apk add --no-cache zip git mariadb-client postgresql-client postgresql-dev sqlite zip libzip-dev; \
	docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp; \
  docker-php-ext-enable xdebug pcov; \
	mkdir /ssl-certs; \
	docker-php-source delete; \
	rm -f /usr/src/php.tar.xz /usr/src/php.tar.xz.asc; \
	apk del --no-cache postgresql-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 10000
