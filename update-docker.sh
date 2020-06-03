#!/bin/bash
# (C) 2020 Kevin Woblick / LinkAce

CR='\033[0;31m'
CG='\033[0;32m'
CY='\033[0;33m'
CW='\033[0m'

printf "\n${CG}==============================\n"
printf " LinkAce Docker Update Script \n"
printf "==============================\n\n"

printf "${CW}This script assumes you are using the standard Docker setup for Linkace. It is only save to use if you did not modify any Docker-related files.\nPlease make a backup of your database before you continue.\n"

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
docker-compose down

printf "\n> Deleting the application container volume...\n"
docker volume rm linkace_linkace_app

printf "\n> Pulling latest LinkAce image from the Docker Hub...\n"
docker pull linkace/linkace

printf "\n> Restarting the application...\n"
docker-compose up -d

printf "\n${CG}The LinkAce image was successfully updated.${CW}\n"

# Confirm that the user wants to update now
printf "\n${CY}You should upgrade the database now. Should the script take care of it? [y/n]${CW} "
read -n 1 -r
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
  printf "\nYou can manually update the database by running"
  printf "\n$ docker-compose run php php artisan migrate\n"
  exit;
fi

printf "\n> Migrating database...\n"
docker-compose run php php artisan migrate --force

printf "\n${CG}LinkAce was upgraded successfully!\n"
