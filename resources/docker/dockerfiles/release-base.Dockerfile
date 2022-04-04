FROM php:8.1-fpm-alpine

# Install package and PHP dependencies
RUN apk add --no-cache mariadb-client postgresql postgresql-dev zip libzip-dev; \
	docker-php-ext-configure zip; \
	docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip; \
	mkdir /ssl-certs
