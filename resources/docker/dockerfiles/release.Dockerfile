# DOCKERFILE RELEASE

# ================================
# PHP Dependency Setup
FROM bitnami/php-fpm:7.2 AS builder

# Make composer files available in the container
COPY ./ /app
COPY ./.env.example /app/.env

WORKDIR /app

# Install dependencies using Composer
RUN composer install -n --prefer-dist --no-dev

# ================================
# Compile all assets
FROM node:10.15.0 AS npm_builder

# Copy package.json and Gruntfile
COPY ./package.json /srv
COPY ./Gruntfile.js /srv
WORKDIR /srv

RUN npm install

# Copy the app and build the assets
COPY ./resources/assets /srv/resources/assets
COPY ./public /srv/public
RUN npm run build

# ================================
# Prepare the final image
FROM bitnami/php-fpm:7.2-prod

WORKDIR /app
COPY ./ /app
COPY ./.env.example /app/.env

# Copy the PHP config files
COPY ./resources/docker/php/php.ini /opt/bitnami/php/etc/conf.d/php.ini

# Install MySQL Dump for automated backups
RUN install_packages mysql-client

# Copy files from the composer build
COPY --from=builder /app/vendor /app/vendor
COPY --from=builder /app/bootstrap/cache /app/bootstrap/cache

# Publish package resources
RUN php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Copy files from the theme build
COPY --from=npm_builder /srv/public /app/public

# Cleanup dev stuff from final image
RUN rm -rf /app/node_modules

# Set correct permissions for the storage directory
RUN chmod 0777 -R /app/storage

WORKDIR /app
