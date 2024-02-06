FROM php:8.3-fpm-alpine

# Install package and PHP dependencies
RUN apk add --no-cache mariadb-client postgresql-client libpq-dev sqlite zip libzip-dev supervisor \
	&& docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp \
	&& mkdir /ssl-certs \
  && mkdir /etc/supervisor.d \
  && mkdir /etc/caddy \
  && docker-php-source delete

# Copy Caddy executable
COPY --from=caddy:2 /usr/bin/caddy /usr/bin/caddy
