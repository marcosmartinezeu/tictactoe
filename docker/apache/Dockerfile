FROM debian:8

# Install apache and enable mods
RUN apt-get update && apt-get -y upgrade \
        && DEBIAN_FRONTEND=noninteractive apt-get -y install apache2 \
        && DEBIAN_FRONTEND=noninteractive apt-get -y install wget \
        && a2enmod rewrite && a2enmod headers && a2enmod expires && a2enmod deflate \
        && a2enmod proxy && a2enmod proxy_fcgi

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Install packages
RUN apt-get update && apt-get -y upgrade

#  Configuring Apache
COPY apache2.conf /etc/apache2/apache2.conf
RUN  rm /etc/apache2/sites-available/000-default.conf \
         && rm /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/html

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND