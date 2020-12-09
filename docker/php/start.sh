#!/bin/bash

set -e

usermod -u $WEB_UID www-data

chown www-data -R /var/www
su -c "composer install -d /var/www/tekstove-api" -s /bin/sh www-data
apachectl -D FOREGROUND
