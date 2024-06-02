FROM docker.io/library/php:8.3-fpm-alpine

# Install package and PHP dependencies
RUN apk add --no-cache mariadb-client postgresql postgresql-dev sqlite zip libzip-dev; \
	docker-php-ext-configure zip; \
	docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp; \
	mkdir /ssl-certs; \
	docker-php-source delete; \
	rm -f /usr/src/php.tar.xz /usr/src/php.tar.xz.asc; \
	apk del --no-cache postgresql-dev libzip-dev
