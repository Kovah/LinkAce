#!/bin/bash
# (C) 2020 Kevin Woblick / LinkAce
set -e

CR='\033[0;31m'
CG='\033[0;32m'
CY='\033[0;33m'
CW='\033[0m'

printf "\n${CG}==============================\n"
printf " LinkAce Docker Update Script \n"
printf "==============================\n\n"

printf "${CW}This script assumes you are using the standard Docker setup for Linkace. Only use this script if you did NOT modify any Docker-related files.\n${CR}PLEASE MAKE A BACKUP OF YOUR DATABASE BEFORE YOU CONTINUE!\n"

# Confirm that the user wants to update now
printf "\n${CR}Do you want to upgrade LinkAce now? [y/n]${CW} "
read -n 1 -r
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit;
fi

# Check if Docker and docker-compose are installed
command -v docker-compose >/dev/null 2>&1 || {
  printf "\n${CR}Docker and docker-compose must be installed for this script to work. Aborting.${CW}\n";
  exit 1;
}

printf "\n> Deleting the application container volume...\n"
docker-compose stop
if grep -q "linkace/linkace:simple" docker-compose.yml; then
  docker container rm linkace_app_1
else
  docker container rm linkace_app_1
  docker container rm linkace_nginx_1
fi

printf "\n> Deleting the application container volume...\n"
docker volume rm linkace_linkace_app

printf "\n> Pulling latest LinkAce image from the Docker Hub...\n"
if grep -q "linkace/linkace:simple" docker-compose.yml; then
  docker pull linkace/linkace:simple
else
  docker pull linkace/linkace
fi

printf "\n> Restarting the application...\n"
docker-compose up -d

printf "\n${CG}The LinkAce image was successfully updated.${CW}\n"

# Confirm that the user wants to update now
printf "\n${CY}You should upgrade the database now. Should the script take care of it? [y/n]${CW} "
read -n 1 -r
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
  printf "\nYou can manually update the database by running"
  printf "\n$ docker-compose run app php artisan migrate\n"
  exit;
fi

printf "\n> Migrating database...\n"
docker-compose run app php artisan migrate --force

printf "\n${CG}LinkAce was upgraded successfully!\n"

# Ask the user for caching
printf "\n${CR}Should the configuration and routes be cached now? This might improve overall performance. [y/n]${CW} "
read -n 1 -r
if [[ ! $REPLY =~ ^[Yy]$ ]]; then exit;
fi

docker-compose run app php artisan config:cache
docker-compose run app php artisan route:cache

printf "\n${CG}Thanks for using LinkAce! :)\n"
