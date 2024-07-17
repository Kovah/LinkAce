FROM docker.io/library/php:8.3-fpm-alpine

# Install package and PHP dependencies
RUN apk add --no-cache mariadb-client postgresql-client postgresql-dev sqlite zip libzip-dev supervisor \
  && docker-php-ext-configure zip; \
	&& docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp \
	&& mkdir /ssl-certs \
  && mkdir /etc/supervisor.d \
  && mkdir /etc/caddy \
  && docker-php-source delete \
  && rm -f /usr/src/php.tar.xz /usr/src/php.tar.xz.asc \
  && apk del --no-cache postgresql-dev

# Copy Caddy executable
COPY --from=caddy:2 /usr/bin/caddy /usr/bin/caddy
