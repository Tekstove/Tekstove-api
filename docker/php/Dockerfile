FROM debian:10

RUN apt-get update \
    && apt-get install -y apt-transport-https lsb-release ca-certificates wget vim gnupg2

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN apt-get update && apt-get install -y \
    git unzip curl \
    apache2 libapache2-mod-php

ENV PHP_V="7.4"

RUN apt-get update \
    && apt-get install -y \
        php${PHP_V} \
        php${PHP_V}-xml \
        php${PHP_V}-mysql \
        php${PHP_V}-mbstring

RUN update-alternatives --set php /usr/bin/php${PHP_V}

RUN a2enmod actions rewrite

RUN wget "https://getcomposer.org/download/1.9.3/composer.phar" -q -O /bin/composer && chmod +x /bin/composer

RUN a2enmod proxy_http ssl
COPY apache-api.conf /etc/apache2/sites-available/tekstove-api.conf
COPY apache-api-ssl.conf /etc/apache2/sites-available/tekstove-api-ssl.conf
COPY apache-shared.conf /etc/apache2/sites-available/tekstove-shared.conf
RUN  a2dissite 000-default && a2ensite tekstove-api && a2ensite tekstove-api-ssl
RUN openssl req -x509 -nodes -days 365 -subj "/C=BG/" -new -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt

WORKDIR /var/www/tekstove-api

ADD start.sh /start.sh

CMD bash /start.sh
