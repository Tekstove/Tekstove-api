#!/bin/bash

set -e

chown site_tekstove_api -R /var/www/tekstove-api
su -c "composer install -d /var/www/tekstove-api" -s /bin/sh site_tekstove_api
/etc/init.d/apache2 restart
tail -F /var/log/apache2/error.log -F /var/www/tekstove-api/var/log/dev.log
