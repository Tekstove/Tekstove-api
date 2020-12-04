#!/bin/bash

set -e

PROJECT_NAME=tekstove

# If the script is run with sudo, UID is 0. This is an issue when running
# "usermod -u $WEB_UID www-data" in the web container.
# In this case assign WEB_UID to 1000
[[ $UID == 0 ]] && export WEB_UID=1000 || export WEB_UID=$UID

cd docker

docker build --tag=tekstove-api-php ./php/
docker-compose -p $PROJECT_NAME build

if [[ "$1" == "-p" ]]; then
    docker-compose -p $PROJECT_NAME -f docker-compose.yml -f up
else
    docker-compose -p $PROJECT_NAME -f docker-compose.yml -f docker-compose-dev.yml up
fi
