FROM docker.io/library/php:8.5-fpm-alpine

# Install package and PHP dependencies
# $PHPIZE_DEPS provides the toolchain (gcc, make, autoconf, ...) needed to
# compile PHP extensions. It is removed again once the extensions are built.
RUN apk add --no-cache mariadb-client postgresql-client sqlite zip libzip supervisor \
	&& apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libzip-dev postgresql-dev \
	&& docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ftp \
	&& mkdir /ssl-certs \
  && mkdir /etc/supervisor.d \
  && mkdir /etc/caddy \
  && docker-php-source delete \
  && rm -f /usr/src/php.tar.xz /usr/src/php.tar.xz.asc \
  && apk del --no-cache .build-deps

# Copy Caddy executable
COPY --from=caddy:2 /usr/bin/caddy /usr/bin/caddy
