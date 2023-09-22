FROM php:8.0-fpm-alpine

# Install package and PHP dependencies
RUN apk add --no-cache mariadb-client postgresql postgresql-dev sqlite zip libzip-dev; \
	docker-php-ext-configure zip; \
	docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip; \
	mkdir /ssl-certs; \
  docker-php-source delete
