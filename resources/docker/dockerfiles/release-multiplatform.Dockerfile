# DOCKERFILE RELEASE

# ================================
# PHP Dependency Setup
FROM composer AS builder
WORKDIR /app

# Make needed parts of the app available in the container
COPY ./app /app/app
COPY ./bootstrap /app/bootstrap
COPY ./config /app/config
COPY ./database /app/database
COPY ./resources /app
COPY ./routes /app/routes
COPY ./tests /app/tests

COPY ./artisan /app
COPY ./composer.json /app
COPY ./composer.lock /app
COPY ./README.md /app
COPY ./.env.example /app/.env

# Install dependencies using Composer
RUN composer install -n --prefer-dist --no-dev

# ================================
# Compile all assets
FROM node:14 AS npm_builder
WORKDIR /srv

# Copy package.json and Gruntfile
COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./webpack.mix.js ./
COPY ./resources/assets ./resources/assets

RUN npm install
RUN npm run production

# ================================
# Prepare the final image
FROM php:8.0-fpm-alpine
WORKDIR /app

# Copy the app into the container
COPY ./app /app/app
COPY ./bootstrap /app/bootstrap
COPY ./config /app/config
COPY ./database /app/database
COPY ./public /app/public
COPY ./resources /app/resources
COPY ./routes /app/routes
COPY ./storage /app/storage
COPY ./tests /app/tests

COPY ./artisan /app
COPY ./composer.json /app
COPY ./composer.lock /app
COPY ./README.md /app
COPY ./package.json /app
COPY ./server.php /app
COPY ./.env.example /app/.env

# Copy the PHP and nginx config files
COPY ./resources/docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./resources/docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Install nginx, MySQL Dump for automated backups and other dependencies
RUN apk add --no-cache mariadb-client postgresql postgresql-dev zip libzip-dev ; \
	docker-php-ext-configure zip ; \
	docker-php-ext-install bcmath pdo_mysql pdo_pgsql zip ; \
	mkdir /ssl-certs

# Copy files from the composer build
COPY --from=builder /app/vendor /app/vendor
COPY --from=builder /app/bootstrap/cache /app/bootstrap/cache

# Publish package resources
RUN php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Copy files from the theme build
COPY --from=npm_builder /srv/public/assets/dist/js /app/public/assets/dist/js
COPY --from=npm_builder /srv/public/assets/dist/css /app/public/assets/dist/css
COPY --from=npm_builder /srv/public/mix-manifest.json /app/public/mix-manifest.json

# Set correct permissions for the storage directory
RUN chmod -R 0777 /app/storage
