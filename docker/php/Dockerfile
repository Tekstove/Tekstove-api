FROM debian:8

RUN apt-get update \
    && apt-get install -y apt-transport-https lsb-release ca-certificates wget vim
RUN cd /root && wget https://www.dotdeb.org/dotdeb.gpg && apt-key add dotdeb.gpg

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN echo "deb http://ftp.bg.debian.org/debian/ jessie main contrib non-free" >> /etc/apt/sources.list
RUN echo "deb-src http://ftp.bg.debian.org/debian/ jessie main contrib non-free" >> /etc/apt/sources.list
RUN apt-get update \
    && apt-get install -y php7.1-cgi php7.1-xml php7.1-mysql php7.1-mbstring git unzip curl apache2 libapache2-mod-fastcgi apache2-mpm-worker apache2-suexec libapache2-mod-fcgid
RUN a2enmod actions fastcgi rewrite

RUN wget "https://getcomposer.org/download/1.4.2/composer.phar" -q -O /bin/composer && chmod +x /bin/composer

RUN a2enmod proxy_http ssl suexec
COPY tekstove-api.conf /etc/apache2/sites-available/tekstove-api.conf
COPY tekstove-api-ssl.conf /etc/apache2/sites-available/tekstove-api-ssl.conf
RUN  a2dissite 000-default && a2ensite tekstove-api && a2ensite tekstove-api-ssl
RUN openssl req -x509 -nodes -days 365 -subj "/C=BG/" -new -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt
RUN sed 's/.*cgi.fix_pathinfo=.*/cgi.fix_pathinfo=1/' /etc/php/7.1/cgi/php.ini > /etc/php/7.1/cgi/php.ini
RUN groupadd site_tekstove_api
RUN useradd -s /bin/false -d /home/site_tekstove_api -m -g site_tekstove_api site_tekstove_api
RUN mkdir -p /home/site_tekstove_api
RUN chown -R site_tekstove_api:site_tekstove_api /home/site_tekstove_api
RUN mkdir -p /var/www/php-fcgi-scripts/tekstove_api
COPY fcgi-starter /var/www/php-fcgi-scripts/tekstove_api/php-fcgi-starter
RUN chown -R site_tekstove_api:site_tekstove_api /var/www/php-fcgi-scripts/tekstove_api
RUN chmod 755 /var/www/php-fcgi-scripts/tekstove_api/php-fcgi-starter

WORKDIR /var/www/tekstove-api

CMD if [ ! -d "/var/www/tekstove-api/var/cache" ] ; \
    then composer install -d /var/www/tekstove-api ; \
    fi ; \
    chmod 777 -R /var/www/tekstove-api/var/* && \
    /etc/init.d/apache2 restart && \
    tail -f /var/log/apache2/error.log
