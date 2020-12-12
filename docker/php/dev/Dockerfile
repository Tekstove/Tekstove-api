FROM tekstove-api-php

ENV PHP_V="7.4"

RUN apt-get update \
    && apt-get install -y \
        php${PHP_V}-xdebug

RUN update-alternatives --set php /usr/bin/php${PHP_V}
RUN echo "xdebug.remote_connect_back=1" >> /etc/php/${PHP_V}/cgi/conf.d/20-xdebug.ini
RUN echo "xdebug.remote_enable=1" >> /etc/php/${PHP_V}/cgi/conf.d/20-xdebug.ini
