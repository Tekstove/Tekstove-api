#!/bin/bash

set -e

export PROJECT_NAME=tekstove

docker network inspect $PROJECT_NAME || docker network create $PROJECT_NAME

# If the script is run with sudo, UID is 0. This is an issue when running
# "usermod -u $WEB_UID www-data" in the web container.
# In this case assign WEB_UID to 1000
[[ $UID == 0 ]] && export WEB_UID=1000 || export WEB_UID=$UID

cd docker

docker build --tag=tekstove-api-php ./php/

if [[ "$1" == "-p" ]]; then
    docker-compose -p $PROJECT_NAME -f docker-compose.yml build
    docker-compose -p $PROJECT_NAME -f docker-compose.yml up
else
    docker-compose -p $PROJECT_NAME -f docker-compose.yml -f docker-compose-dev.yml build
    docker-compose -p $PROJECT_NAME -f docker-compose.yml -f docker-compose-dev.yml up
fi
