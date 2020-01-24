# DOCKERFILE DEVELOPMENT
FROM bitnami/php-fpm:7.4-prod

# Install MySQL Dump for automated backups
RUN install_packages mariadb-client

WORKDIR /app
