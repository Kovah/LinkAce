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
FROM node:14.15.4 AS npm_builder
WORKDIR /srv

# Copy package.json and Gruntfile
COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./webpack.mix.js ./
COPY ./resources/assets ./resources/assets

RUN npm install
RUN npm run production

# ================================
# Prepare the final image including nginx
FROM webdevops/php-nginx:8.0-alpine
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

# Copy the PHP config files
COPY ./resources/docker/php/php.ini /opt/docker/etc/php/php.ini

# Copy files from the composer build
COPY --from=builder /app/vendor /app/vendor
COPY --from=builder /app/bootstrap/cache /app/bootstrap/cache

# Install MySQL Dump for automated backups
RUN apk add --no-cache mariadb-client

# Publish package resources
RUN php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Copy files from the theme build
COPY --from=npm_builder /srv/public/assets/dist/js /app/public/assets/dist/js
COPY --from=npm_builder /srv/public/assets/dist/css /app/public/assets/dist/css
COPY --from=npm_builder /srv/public/mix-manifest.json /app/public/mix-manifest.json

# Set correct permissions for the storage directory
RUN chown -R application:application /app
RUN chmod -R 0777 /app/storage

ENV WEB_DOCUMENT_ROOT /app/public
