# DOCKERFILE RELEASE

# ================================
# PHP Dependency Setup
FROM docker.io/linkace/base-image:php-8.3-caddy AS builder
WORKDIR /app

# Pull composer and install required packages
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apk add --no-cache git

# Make needed parts of the app available in the container
COPY ./app /app/app
COPY ./bootstrap /app/bootstrap
COPY ./config /app/config
COPY ./database /app/database
COPY ./lang /app/lang
COPY ./resources /app/resources
COPY ./routes /app/routes
COPY ./tests /app/tests

COPY ["./artisan", "./composer.json", "./composer.lock", "/app/"]
COPY ./.env.example /app/.env

# Install dependencies using Composer
RUN composer install -n --prefer-dist --no-dev

RUN mv vendor/spatie/laravel-backup/resources/lang/de vendor/spatie/laravel-backup/resources/lang/de_DE; \
  mv vendor/spatie/laravel-backup/resources/lang/en vendor/spatie/laravel-backup/resources/lang/en_US; \
  mv vendor/spatie/laravel-backup/resources/lang/es vendor/spatie/laravel-backup/resources/lang/es_ES; \
  mv vendor/spatie/laravel-backup/resources/lang/fr vendor/spatie/laravel-backup/resources/lang/fr_FR; \
  mv vendor/spatie/laravel-backup/resources/lang/it vendor/spatie/laravel-backup/resources/lang/it_IT; \
  mv vendor/spatie/laravel-backup/resources/lang/no vendor/spatie/laravel-backup/resources/lang/no_NO; \
  mv vendor/spatie/laravel-backup/resources/lang/pl vendor/spatie/laravel-backup/resources/lang/pl_PL; \
  mv vendor/spatie/laravel-backup/resources/lang/ro vendor/spatie/laravel-backup/resources/lang/zh_CN; \
  mv vendor/spatie/laravel-backup/resources/lang/ru vendor/spatie/laravel-backup/resources/lang/ro_RO; \
  mv vendor/spatie/laravel-backup/resources/lang/zh-CN vendor/spatie/laravel-backup/resources/lang/ru_RU

# ================================
# Compile all assets
FROM docker.io/library/node:20 AS npm_builder
WORKDIR /srv

COPY ./resources/assets ./resources/assets
COPY ["./package.json", "./package-lock.json", "./webpack.mix.js", "/srv/"]

RUN npm install
RUN npm run production

# ================================
# Prepare the final image
FROM docker.io/linkace/base-image:php-8.3-caddy
WORKDIR /app
USER www-data

# Copy the app into the container
COPY --chown=www-data:www-data ./app /app/app
COPY --chown=www-data:www-data ./bootstrap /app/bootstrap
COPY --chown=www-data:www-data ./config /app/config
COPY --chown=www-data:www-data ./database /app/database
COPY --chown=www-data:www-data ./public /app/public
COPY --chown=www-data:www-data ./lang /app/lang
COPY --chown=www-data:www-data ./resources /app/resources
COPY --chown=www-data:www-data ./routes /app/routes
COPY --chown=www-data:www-data ./storage /app/storage

COPY --chown=www-data:www-data ["./artisan", "./composer.json", "./composer.lock", "./README.md", "./LICENSE.md", "./package.json", "/app/"]
COPY --chown=www-data:www-data ./.env.example /app/.env

# Copy files from the composer build
COPY --from=builder --chown=www-data:www-data /app/vendor /app/vendor
COPY --from=builder --chown=www-data:www-data /app/bootstrap/cache /app/bootstrap/cache

# Publish package resources
RUN php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Copy files from the theme build
COPY --from=npm_builder --chown=www-data:www-data /srv/public/assets/dist/js /app/public/assets/dist/js
COPY --from=npm_builder --chown=www-data:www-data /srv/public/assets/dist/css /app/public/assets/dist/css
COPY --from=npm_builder --chown=www-data:www-data /srv/public/mix-manifest.json /app/public/mix-manifest.json

# Create a SQLite database file ready to be used
RUN touch ./database/database.sqlite

# Configure Supervisor for PHP + Caddy
ENV PORT=80
ENV CADDY_PHP_HOST=localhost
COPY ./resources/docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY ./resources/docker/supervisord.ini /etc/supervisor.d/supervisord.ini
COPY ./resources/docker/Caddyfile /etc/caddy/Caddyfile

USER root
EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]
