# DOCKERFILE DEVELOPMENT
# Installs MySQL Client for database exports, and xDebug
FROM bitnami/php-fpm:7.4

RUN install_packages mariadb-client autoconf build-essential php-pear

RUN pecl install xdebug pcov

EXPOSE 10000
